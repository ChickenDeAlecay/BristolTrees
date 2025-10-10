<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Tree;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Tree $tree)
    {
        if (!auth()->check()) {
            abort(403, 'You must be logged in to leave a review.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'tree_id' => $tree->id,
            'content' => $request->content,
        ]);

        return redirect()->route('trees.show', $tree)->with('success', 'Review added successfully!');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }

        $review->delete();

        return redirect()->back()->with('success', 'Review deleted successfully!');
    }
}
