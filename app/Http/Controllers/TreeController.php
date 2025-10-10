<?php

namespace App\Http\Controllers;

use App\Models\Tree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TreeController extends Controller
{
    public function index()
    {
        $trees = Tree::with(['ratings', 'approvedImages'])->get();
        return view('trees.index', compact('trees'));
    }

    public function show($id)
    {
        $tree = Tree::with(['reviews.user', 'ratings', 'approvedImages'])->findOrFail($id);
        $averageRating = $tree->averageRating();
        $userRating = null;
        
        if (auth()->check()) {
            $userRating = $tree->ratings()->where('user_id', auth()->id())->first();
        }
        
        return view('trees.show', compact('tree', 'averageRating', 'userRating'));
    }

    public function sync()
    {
        $url = 'https://maps2.bristol.gov.uk/server2/rest/services/ext/ll_environment_and_planning/MapServer/32/query?where=1%3D1&outFields=TYPE,X,Y,DEAD,LATIN_NAME,COMMON_NAME,CROWN_HEIGHT,TREE_SPECIES,ASSET_ID&outSR=4326&f=json';
        
        try {
            $response = Http::get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['features'])) {
                    foreach ($data['features'] as $feature) {
                        $attributes = $feature['attributes'];
                        
                        Tree::updateOrCreate(
                            ['asset_id' => $attributes['ASSET_ID']],
                            [
                                'type' => $attributes['TYPE'] ?? null,
                                'x' => $feature['geometry']['x'] ?? 0,
                                'y' => $feature['geometry']['y'] ?? 0,
                                'dead' => $attributes['DEAD'] === 'Y',
                                'latin_name' => $attributes['LATIN_NAME'] ?? null,
                                'common_name' => $attributes['COMMON_NAME'] ?? null,
                                'crown_height' => $attributes['CROWN_HEIGHT'] ?? null,
                                'tree_species' => $attributes['TREE_SPECIES'] ?? null,
                            ]
                        );
                    }
                    
                    return redirect()->route('trees.index')->with('success', 'Trees synced successfully!');
                }
            }
            
            return redirect()->route('trees.index')->with('error', 'Failed to sync trees.');
        } catch (\Exception $e) {
            return redirect()->route('trees.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function getTreesJson()
    {
        $trees = Tree::with(['approvedImages'])->get()->map(function ($tree) {
            return [
                'id' => $tree->id,
                'asset_id' => $tree->asset_id,
                'lat' => (float) $tree->y,
                'lng' => (float) $tree->x,
                'common_name' => $tree->common_name,
                'latin_name' => $tree->latin_name,
                'type' => $tree->type,
                'dead' => $tree->dead,
                'crown_height' => $tree->crown_height,
                'tree_species' => $tree->tree_species,
                'average_rating' => round($tree->averageRating() ?? 0, 1),
                'image' => $tree->approvedImages->first()?->image_path,
            ];
        });

        return response()->json($trees);
    }
}
