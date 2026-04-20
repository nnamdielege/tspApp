<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Route Map
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                
                <div class="lg:col-span-1 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Route Summary
                    </h3>

                    <div class="mb-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Optimization</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            {{ $route->optimize_type === 'duration' ? 'Duration' : 'Distance' }}
                        </p>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Weight</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            {{ $route->total_weight }}
                        </p>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Stops</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            {{ count($orderedStops) }}
                        </p>
                    </div>

                    <div class="space-y-3 max-h-[60vh] overflow-y-auto">
                        @foreach($orderedStops as $index => $stop)
                            <div class="border rounded-lg p-3 dark:border-gray-700">
                                <p class="font-semibold text-gray-900 dark:text-gray-100">
                                    Stop {{ $index + 1 }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $stop['address'] ?? 'Unknown address' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-3">
                    <div id="map" class="w-full rounded-lg shadow" style="height: 75vh; min-height: 500px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const orderedStops = @json($orderedStops);

        function initMap() {
            if (!orderedStops || orderedStops.length === 0) {
                alert('No stops available for this route.');
                return;
            }

            const firstStop = orderedStops[0];

            const map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: parseFloat(firstStop.lat),
                    lng: parseFloat(firstStop.lng)
                },
                zoom: 10
            });

            const bounds = new google.maps.LatLngBounds();
            const path = [];

            orderedStops.forEach((stop, index) => {
                const position = {
                    lat: parseFloat(stop.lat),
                    lng: parseFloat(stop.lng)
                };

                const marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    label: String(index + 1),
                    title: stop.address || `Stop ${index + 1}`
                });

                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 4px 6px;">
                            <strong>Stop ${index + 1}</strong><br>
                            ${stop.address || 'Unknown address'}
                        </div>
                    `
                });

                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });

                bounds.extend(position);
                path.push(position);
            });

            const polyline = new google.maps.Polyline({
                path: path,
                geodesic: true,
                strokeOpacity: 1.0,
                strokeWeight: 4
            });

            polyline.setMap(map);
            map.fitBounds(bounds);
        }
    </script>

    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&callback=initMap">
    </script>
</x-app-layout>