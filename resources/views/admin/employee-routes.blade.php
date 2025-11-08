<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.employees') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">← Back</a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Employee Routes') }}
                </h2>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span>Live • Updated: <span id="lastUpdate">now</span></span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Employee Info Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100" id="employeeName">Loading...</h3>
                        <p class="text-gray-600 dark:text-gray-400" id="employeeEmail">-</p>
                    </div>
                    <div class="text-4xl">🚗</div>
                </div>
            </div>

            <!-- Route Statistics - Live Update -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold text-blue-100">Total Routes</h3>
                        <span class="text-xl">📊</span>
                    </div>
                    <div class="text-4xl font-bold" id="totalRoutes">0</div>
                    <p class="text-xs text-blue-100 mt-2">All time routes</p>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow p-6 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold text-green-100">Completed</h3>
                        <span class="text-xl">✓</span>
                    </div>
                    <div class="text-4xl font-bold" id="completedRoutes">0</div>
                    <p class="text-xs text-green-100 mt-2"><span id="completionRate">0</span>% completion</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
                <div class="flex gap-4 flex-wrap">
                    <select id="routeFilter" class="px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                        <option value="all">All Routes</option>
                        <option value="completed">Completed</option>
                        <option value="in_progress">In Progress</option>
                        <option value="planned">Planned</option>
                    </select>
                    <button onclick="loadEmployeeRoutes()" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition">
                        Filter
                    </button>
                    <button onclick="refreshData()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                        🔄 Refresh
                    </button>
                </div>
            </div>

            <!-- Routes Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Routes List</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700 border-b dark:border-gray-600">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Optimal Path</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">Stops</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">Total Weight</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">Optimize Type</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">Status</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">Date</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="routesTableBody">
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center">
                                    <div class="flex justify-center items-center">
                                        <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="ml-2">Loading routes...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Route Details Modal -->
            <div id="routeDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-96 overflow-y-auto">
                    <div class="p-6 border-b dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800 flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100" id="modalRouteTitle">Route Details</h3>
                        <button onclick="closeRouteModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 text-2xl">&times;</button>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Total Distance</label>
                                <p class="text-gray-900 dark:text-gray-100" id="modalDistance">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Total Duration</label>
                                <p class="text-gray-900 dark:text-gray-100" id="modalDuration">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Total Stops</label>
                                <p class="text-gray-900 dark:text-gray-100" id="modalStops">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <p class="text-gray-900 dark:text-gray-100" id="modalStatus">-</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Stops</label>
                            <div id="stopsList" class="space-y-2">
                                <!-- Stops will be populated here -->
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-t dark:border-gray-700 flex justify-end">
                        <button onclick="closeRouteModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-gray-100 rounded hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Get employee ID from URL path
        const pathParts = window.location.pathname.split('/');
        const employeeId = pathParts[pathParts.length - 2];
        let autoRefreshInterval;

        console.log('Employee ID from URL:', employeeId);

        document.addEventListener('DOMContentLoaded', function() {
            if (!employeeId || isNaN(employeeId)) {
                console.error('Invalid employee ID:', employeeId);
                document.getElementById('routesTableBody').innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-red-500">Invalid employee ID</td></tr>';
                return;
            }
            
            console.log('Loading routes for employee:', employeeId);
            loadEmployeeRoutes();
            loadRouteStatistics();
            
            // Auto-refresh every 5 seconds
            autoRefreshInterval = setInterval(() => {
                loadEmployeeRoutes();
                loadRouteStatistics();
                updateLastUpdate();
            }, 5000);
        });

        function updateLastUpdate() {
            const now = new Date();
            const time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('lastUpdate').textContent = time;
        }

        function refreshData() {
            loadEmployeeRoutes();
            loadRouteStatistics();
            updateLastUpdate();
        }

        function loadEmployeeRoutes() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const filter = document.getElementById('routeFilter').value;
            const url = `/admin/employees/${employeeId}/routes?filter=${filter}`;

            console.log('Fetching routes from:', url);

            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Routes data received:', data);
                let routesArray = Array.isArray(data) ? data : (data.routes || []);
                renderRoutesTable(routesArray);
            })
            .catch(error => {
                console.error('Error loading routes:', error);
                document.getElementById('routesTableBody').innerHTML = `<tr><td colspan="7" class="px-6 py-8 text-center text-red-500">Error loading routes: ${error.message}</td></tr>`;
            });
        }

        function renderRoutesTable(routes) {
            const tableBody = document.getElementById('routesTableBody');

            if (!routes || !Array.isArray(routes) || routes.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">No routes found for this employee</td></tr>';
                return;
            }

            tableBody.innerHTML = routes.map(route => `
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">Route #${route.id}</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-600 dark:text-gray-400">${route.location_count || 0}</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-600 dark:text-gray-400">${route.total_weight || '-'}</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-600 dark:text-gray-400">${route.optimize_type || 'N/A'}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold ${
                            route.status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                            route.status === 'in_progress' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                            'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
                        }">
                            ${(route.status || 'pending').replace(/_/g, ' ').toUpperCase()}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm text-gray-600 dark:text-gray-400">${route.date_created || '-'}</td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="viewRouteDetails(${route.id})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-sm font-semibold">
                            Details
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function loadRouteStatistics() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const url = `/admin/employees/${employeeId}/routes/statistics`;

            console.log('Loading statistics from:', url);

            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                console.log('Statistics response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(stats => {
                console.log('Statistics data:', stats);
                
                const total = stats.total_routes_created || 0;
                const completed = stats.total_routes_completed || 0;
                const rate = total > 0 ? Math.round((completed / total) * 100) : 0;

                console.log('Updating UI:', { total, completed, rate });

                document.getElementById('totalRoutes').textContent = total;
                document.getElementById('completedRoutes').textContent = completed;
                document.getElementById('completionRate').textContent = rate;
            })
            .catch(error => {
                console.error('Error loading statistics:', error);
                document.getElementById('totalRoutes').textContent = '0';
                document.getElementById('completedRoutes').textContent = '0';
                document.getElementById('completionRate').textContent = '0';
            });
        }

        function viewRouteDetails(routeId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const url = `/admin/routes/${routeId}/details`;

            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalRouteTitle').textContent = data.name || 'Route Details';
                document.getElementById('modalDistance').textContent = (data.total_distance || 0) + ' km';
                document.getElementById('modalDuration').textContent = data.duration || '-';
                document.getElementById('modalStops').textContent = data.location_count || 0;
                document.getElementById('modalStatus').textContent = (data.status || 'unknown').toUpperCase();

                const stopsList = document.getElementById('stopsList');
                stopsList.innerHTML = (data.stops || []).map((stop, index) => `
                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded border border-gray-200 dark:border-gray-600">
                        <p class="font-semibold text-gray-900 dark:text-gray-100">Stop ${index + 1}: ${stop.address || 'Location'}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Lat: ${(stop.latitude || 0).toFixed(4)}, Lng: ${(stop.longitude || 0).toFixed(4)}</p>
                    </div>
                `).join('');

                document.getElementById('routeDetailsModal').classList.remove('hidden');
                document.getElementById('routeDetailsModal').classList.add('flex');
            })
            .catch(error => console.error('Error loading route details:', error));
        }

        function closeRouteModal() {
            document.getElementById('routeDetailsModal').classList.add('hidden');
            document.getElementById('routeDetailsModal').classList.remove('flex');
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
        });
    </script>

    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @media (max-width: 768px) {
            table {
                font-size: 0.875rem;
            }

            th, td {
                padding: 0.75rem 0.5rem !important;
            }
        }
    </style>
</x-app-layout>