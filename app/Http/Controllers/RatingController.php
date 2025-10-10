<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Tree;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, Tree $tree)
    {
        if (!auth()->check()) {
            abort(403, 'You must be logged in to rate a tree.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Rating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'tree_id' => $tree->id,
            ],
            [
                'rating' => $request->rating,
            ]
        );

        return redirect()->route('trees.show', $tree)->with('success', 'Rating submitted successfully!');
    }
}
