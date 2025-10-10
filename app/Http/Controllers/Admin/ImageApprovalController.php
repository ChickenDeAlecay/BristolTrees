<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TreeImage;
use Illuminate\Http\Request;

class ImageApprovalController extends Controller
{
    public function index()
    {
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $pendingImages = TreeImage::with(['user', 'tree'])
            ->where('approved', false)
            ->latest()
            ->paginate(10);

        $approvedImages = TreeImage::with(['user', 'tree'])
            ->where('approved', true)
            ->latest()
            ->paginate(10);

        return view('admin.images.index', compact('pendingImages', 'approvedImages'));
    }

    public function approve(TreeImage $image)
    {
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $image->update(['approved' => true]);

        return redirect()->route('admin.images.index')->with('success', 'Image approved successfully!');
    }

    public function reject(TreeImage $image)
    {
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        \Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return redirect()->route('admin.images.index')->with('success', 'Image rejected and deleted successfully!');
    }
}
