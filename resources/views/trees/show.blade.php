<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $tree->common_name ?? $tree->latin_name ?? 'Tree #' . $tree->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Tree Information</h3>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="font-semibold">Common Name:</dt>
                                    <dd>{{ $tree->common_name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-semibold">Latin Name:</dt>
                                    <dd class="italic">{{ $tree->latin_name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-semibold">Type:</dt>
                                    <dd>{{ $tree->type ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-semibold">Species:</dt>
                                    <dd>{{ $tree->tree_species ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-semibold">Crown Height:</dt>
                                    <dd>{{ $tree->crown_height ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-semibold">Status:</dt>
                                    <dd>
                                        @if($tree->dead)
                                            <span class="text-red-600 font-semibold">Dead</span>
                                        @else
                                            <span class="text-green-600 font-semibold">Alive</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="font-semibold">Average Rating:</dt>
                                    <dd>
                                        @if($averageRating)
                                            <span class="text-yellow-500">⭐ {{ number_format($averageRating, 1) }}/5</span>
                                            <span class="text-sm text-gray-600">({{ $tree->ratings->count() }} ratings)</span>
                                        @else
                                            <span class="text-gray-500">No ratings yet</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Location</h3>
                            <div id="tree-map" style="height: 300px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            @if($tree->approvedImages->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Images</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($tree->approvedImages as $image)
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Tree image" class="w-full h-48 object-cover rounded">
                                    <p class="text-xs text-gray-500 mt-1">By {{ $image->user->name }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @auth
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Rate This Tree</h3>
                        <form action="{{ route('ratings.store', $tree) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="flex items-center gap-4">
                                <label for="rating" class="font-semibold">Your Rating:</label>
                                <select name="rating" id="rating" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ $userRating && $userRating->rating == $i ? 'selected' : '' }}>
                                            {{ $i }} {{ $i == 1 ? 'Star' : 'Stars' }}
                                        </option>
                                    @endfor
                                </select>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Submit Rating
                                </button>
                            </div>
                        </form>

                        <h3 class="text-lg font-semibold mb-4 mt-6">Upload an Image</h3>
                        <form action="{{ route('images.store', $tree) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="flex items-center gap-4">
                                <input type="file" name="image" accept="image/*" required class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Upload Image
                                </button>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Images must be approved by an admin before they appear publicly.</p>
                            @error('image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </form>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Write a Review</h3>
                        <form action="{{ route('reviews.store', $tree) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <textarea name="content" rows="4" placeholder="Share your thoughts about this tree..." class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required></textarea>
                                @error('content')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Submit Review
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <p class="text-center">
                            <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700">Login</a> or 
                            <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-700">Register</a> 
                            to rate, review, and upload images of this tree.
                        </p>
                    </div>
                </div>
            @endauth

            @if($tree->reviews->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Reviews</h3>
                        <div class="space-y-4">
                            @foreach($tree->reviews as $review)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="font-semibold">{{ $review->user->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y') }}</p>
                                        </div>
                                        @auth
                                            @if($review->user_id === auth()->id() || auth()->user()->is_admin)
                                                <form action="{{ route('reviews.destroy', $review) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm" onclick="return confirm('Are you sure you want to delete this review?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                    <p>{{ $review->content }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('trees.index') }}" class="text-blue-500 hover:text-blue-700">
                    ← Back to Map
                </a>
            </div>
        </div>
    </div>

    <script>
        function initMap() {
            const location = { lat: {{ $tree->y }}, lng: {{ $tree->x }} };
            
            const map = new google.maps.Map(document.getElementById('tree-map'), {
                zoom: 17,
                center: location
            });

            new google.maps.Marker({
                position: location,
                map: map,
                title: '{{ $tree->common_name ?? "Tree" }}',
                icon: {
                    url: '{{ $tree->dead ? "http://maps.google.com/mapfiles/ms/icons/red-dot.png" : "http://maps.google.com/mapfiles/ms/icons/green-dot.png" }}',
                    scaledSize: new google.maps.Size(32, 32)
                }
            });
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap"></script>
</x-app-layout>
