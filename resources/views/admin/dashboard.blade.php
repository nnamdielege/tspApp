<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
                    Admin Dashboard
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Monitor drivers, employees, and system activity
                </p>
            </div>

            <span class="px-3 py-1 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 text-sm font-semibold">
                👨‍💼 Admin
            </span>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border">
                    <p class="text-sm text-gray-500">Total Employees</p>
                    <p class="text-3xl font-bold mt-2" id="totalEmployees">0</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border">
                    <p class="text-sm text-gray-500">Active</p>
                    <p class="text-3xl font-bold text-green-600 mt-2" id="activeEmployees">0</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border">
                    <p class="text-sm text-gray-500">Suspended</p>
                    <p class="text-3xl font-bold text-red-600 mt-2" id="suspendedEmployees">0</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border">
                    <p class="text-sm text-gray-500">Pending Alerts</p>
                    <p class="text-3xl font-bold text-yellow-500 mt-2" id="pendingReminders">0</p>
                </div>
            </div>

            <!-- Live Tracking -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-semibold">🚚 Live Driver Tracking</h3>
                        <p class="text-sm text-gray-500">Real-time GPS updates</p>
                    </div>

                    <span id="lastUpdated" class="text-xs text-gray-400">
                        Waiting for location...
                    </span>
                </div>

                <div id="map" class="rounded-lg border" style="height: 450px;"></div>
            </div>

            <!-- Action Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 hover:shadow-md transition">
                    <h3 class="text-lg font-semibold mb-2">👥 Employee Management</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Manage employee access and account status.
                    </p>

                    <a href="{{ route('admin.employees') }}"
                    class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm font-medium">
                        Manage Employees
                    </a>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 hover:shadow-md transition">
                    <h3 class="text-lg font-semibold mb-2">📋 Logs & Monitoring</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Review activity logs and send reminders.
                    </p>

                    <a href="{{ route('admin.employee-logs') }}"
                    class="inline-block bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-medium">
                        View Logs
                    </a>
                </div>

                <div style="background:white; padding:20px; margin:20px; border:3px solid purple;">
                    <h2 style="font-size:24px; font-weight:bold; color:#111;">
                        🚚 Assign Optimal Route
                    </h2>

                    <p style="color:#333; margin-bottom:15px;">
                        Assign a saved TSP route to a driver.
                    </p>

                    <a href="{{ route('admin.assign-saved-route') }}"
                    style="background:purple; color:white; padding:12px 20px; border-radius:8px; display:inline-block; text-decoration:none;">
                        Assign Route
                    </a>
                </div>


            </div>

        </div>
    </div>

    <!-- Google Maps -->    
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.api_key') }}">
    </script>

    <script>
        let map;

        // Store markers by driver_id
        let driverMarkers = {};

        document.addEventListener('DOMContentLoaded', () => {
            loadStats();
            initMap();
        });

        function initMap() {

            const defaultPos = {
                lat: -27.4698,
                lng: 153.0251
            };

            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 9,
                center: defaultPos,
            });

            loadDriverLocations();

            // Refresh every 5 seconds
            setInterval(loadDriverLocations, 5000);
        }

        async function loadDriverLocations() {

            try {

                const res = await fetch('/live-driver-locations');

                const drivers = await res.json();

                if (!drivers || drivers.length === 0) {
                    return;
                }

                const bounds = new google.maps.LatLngBounds();

                drivers.forEach(driver => {

                    const position = {
                        lat: parseFloat(driver.lat),
                        lng: parseFloat(driver.lng),
                    };

                    bounds.extend(position);

                    // Existing marker → move it
                    if (driverMarkers[driver.driver_id]) {

                        driverMarkers[driver.driver_id].setPosition(position);

                    } else {

                        // Create new marker
                        const marker = new google.maps.Marker({
                            position: position,
                            map: map,
                            label: `D${driver.driver_id}`,
                            title: driver.driver_name,
                        });

                        const infoWindow = new google.maps.InfoWindow({
                            content: `
                                <div style="padding:5px;">
                                    <strong>${driver.driver_name}</strong><br>
                                    Driver ID: ${driver.driver_id}<br>
                                    Last Update: ${driver.updated_at}
                                </div>
                            `
                        });

                        marker.addListener('click', () => {
                            infoWindow.open(map, marker);
                        });

                        driverMarkers[driver.driver_id] = marker;
                    }
                });

                map.fitBounds(bounds);

                document.getElementById('lastUpdated').textContent =
                    "Updated: " + new Date().toLocaleTimeString();

            } catch (e) {

                console.error('Error loading driver locations:', e);
            }
        }

        function loadStats() {

            fetch('{{ route("admin.suspension-statistics") }}')
                .then(res => res.json())
                .then(data => {

                    document.getElementById('totalEmployees').textContent =
                        data.total_employees;

                    document.getElementById('activeEmployees').textContent =
                        data.active_employees;

                    document.getElementById('suspendedEmployees').textContent =
                        data.suspended_employees;
                });

            fetch('{{ route("admin.reminder-statistics") }}')
                .then(res => res.json())
                .then(data => {

                    document.getElementById('pendingReminders').textContent =
                        data.pending_reminders;
                });
        }
    </script>
</x-app-layout>