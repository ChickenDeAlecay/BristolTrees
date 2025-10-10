<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Bristol Trees Map') }}
            </h2>
            @auth
                @if(auth()->user()->is_admin)
                    <div class="flex gap-2">
                        <a href="{{ route('admin.images.index') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            Admin Dashboard
                        </a>
                        <form action="{{ route('trees.sync') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Sync Trees
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-6">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div id="map" style="height: 600px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let map;
        let markers = [];
        let infoWindow;

        function initMap() {
            // Center on Bristol
            const bristol = { lat: 51.4545, lng: -2.5879 };
            
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: bristol,
                styles: [
                    {
                        featureType: 'poi',
                        elementType: 'labels',
                        stylers: [{ visibility: 'off' }]
                    }
                ]
            });

            infoWindow = new google.maps.InfoWindow();

            // Fetch trees data
            fetch('{{ route("trees.json") }}')
                .then(response => response.json())
                .then(trees => {
                    trees.forEach(tree => {
                        const marker = new google.maps.Marker({
                            position: { lat: tree.lat, lng: tree.lng },
                            map: map,
                            title: tree.common_name || tree.latin_name || 'Unknown Tree',
                            icon: {
                                url: tree.dead ? 'http://maps.google.com/mapfiles/ms/icons/red-dot.png' : 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
                                scaledSize: new google.maps.Size(32, 32)
                            }
                        });

                        marker.addListener('click', () => {
                            const rating = tree.average_rating > 0 ? `⭐ ${tree.average_rating}/5` : 'No ratings yet';
                            const content = `
                                <div style="padding: 10px; max-width: 300px;">
                                    <h3 style="font-weight: bold; font-size: 16px; margin-bottom: 8px;">
                                        ${tree.common_name || 'Unknown'}
                                    </h3>
                                    ${tree.latin_name ? `<p style="font-style: italic; margin-bottom: 8px;">${tree.latin_name}</p>` : ''}
                                    <p style="margin-bottom: 4px;">Type: ${tree.type || 'N/A'}</p>
                                    <p style="margin-bottom: 4px;">Species: ${tree.tree_species || 'N/A'}</p>
                                    <p style="margin-bottom: 4px;">Crown Height: ${tree.crown_height || 'N/A'}</p>
                                    <p style="margin-bottom: 4px;">Status: ${tree.dead ? '<span style="color: red;">Dead</span>' : '<span style="color: green;">Alive</span>'}</p>
                                    <p style="margin-bottom: 8px;">Rating: ${rating}</p>
                                    <a href="/trees/${tree.id}" style="display: inline-block; background-color: #3b82f6; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none;">View Details</a>
                                </div>
                            `;
                            infoWindow.setContent(content);
                            infoWindow.open(map, marker);
                        });

                        markers.push(marker);
                    });
                });
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap"></script>
</x-app-layout>
