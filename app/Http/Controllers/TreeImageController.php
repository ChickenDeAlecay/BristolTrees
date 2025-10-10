<?php

namespace App\Http\Controllers;

use App\Models\TreeImage;
use App\Models\Tree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TreeImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Tree $tree)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('tree_images', $filename, 'public');

            TreeImage::create([
                'user_id' => auth()->id(),
                'tree_id' => $tree->id,
                'image_path' => $path,
                'approved' => false,
            ]);

            return redirect()->route('trees.show', $tree)->with('success', 'Image uploaded successfully! It will be visible after admin approval.');
        }

        return redirect()->route('trees.show', $tree)->with('error', 'Failed to upload image.');
    }

    public function destroy(TreeImage $image)
    {
        if ($image->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully!');
    }
}
