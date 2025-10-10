<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin - Image Approval Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Pending Images ({{ $pendingImages->total() }})</h3>
                    
                    @if($pendingImages->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($pendingImages as $image)
                                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Tree image" class="w-full h-48 object-cover rounded mb-3">
                                    <div class="space-y-2">
                                        <p class="font-semibold">Tree: {{ $image->tree->common_name ?? $image->tree->latin_name ?? 'Tree #' . $image->tree->id }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Uploaded by: {{ $image->user->name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Date: {{ $image->created_at->format('M d, Y H:i') }}</p>
                                        <div class="flex gap-2 mt-4">
                                            <form action="{{ route('admin.images.approve', $image) }}" method="POST" class="flex-1">
                                                @csrf
                                                <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                                    Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.images.reject', $image) }}" method="POST" class="flex-1">
                                                @csrf
                                                <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to reject and delete this image?')">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $pendingImages->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No pending images to review.</p>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Recently Approved Images ({{ $approvedImages->total() }})</h3>
                    
                    @if($approvedImages->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($approvedImages as $image)
                                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-3">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Tree image" class="w-full h-32 object-cover rounded mb-2">
                                    <p class="text-sm font-semibold truncate">{{ $image->tree->common_name ?? 'Tree #' . $image->tree->id }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">By {{ $image->user->name }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $approvedImages->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No approved images yet.</p>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('trees.index') }}" class="text-blue-500 hover:text-blue-700">
                    ← Back to Map
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
