<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 dark:text-gray-400">Here are all your saved optimal routes.</p>
                </div>
            </div>

            <!-- Saved Routes Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold">Your Saved Routes</h3>
                        <a href="{{ route('optimal-path') }}" class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white px-4 py-2 rounded-lg transition">
                            + Create New Route
                        </a>
                    </div>

                    <!-- Routes Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700 border-b dark:border-gray-600">
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Route ID</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Optimization Type</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Total Weight</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Number of Locations</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Created Date</th>
                                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="routesTableBody">
                                <!-- Routes will be loaded here by JavaScript -->
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
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

                    <!-- Empty State -->
                    <div id="emptyState" class="text-center py-12 hidden">
                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="mt-4 text-gray-600 dark:text-gray-400">No saved routes yet. Create your first route!</p>
                        <a href="{{ route('optimal-path') }}" class="mt-4 inline-block bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white px-6 py-2 rounded-lg transition">
                            Create Route
                        </a>
                    </div>
                </div>
            </div>

            <!-- Reminders Widget -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6" id="remindersWidget">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-bold mb-4">📋 Today's Reminders</h3>
                    
                    <div id="remindersContainer">
                        <div class="flex justify-center items-center py-8">
                            <div class="animate-spin h-5 w-5 text-gray-400"></div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="mt-6 grid grid-cols-4 gap-4" id="remindersStats">
                        <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="totalToday">0</div>
                            <div class="text-sm text-blue-600 dark:text-blue-400">Total Today</div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400" id="completedToday">0</div>
                            <div class="text-sm text-green-600 dark:text-green-400">Completed</div>
                        </div>
                        <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400" id="pendingToday">0</div>
                            <div class="text-sm text-yellow-600 dark:text-yellow-400">Pending</div>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-red-600 dark:text-red-400" id="overdue">0</div>
                            <div class="text-sm text-red-600 dark:text-red-400">Overdue</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Route Modal -->
    <div id="viewRouteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-96 overflow-y-auto">
            <div class="p-6 border-b dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Route Details</h3>
                <button onclick="closeViewModal()" class="float-right text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 text-2xl">&times;</button>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Optimal Path:</label>
                    <p id="modalPath" class="bg-gray-100 dark:bg-gray-700 p-4 rounded text-gray-900 dark:text-gray-100 break-words"></p>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Total Weight:</label>
                        <p id="modalWeight" class="text-gray-900 dark:text-gray-100"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Optimization Type:</label>
                        <p id="modalOptimizeType" class="text-gray-900 dark:text-gray-100"></p>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Locations:</label>
                    <ul id="modalLocations" class="list-disc list-inside bg-gray-100 dark:bg-gray-700 p-4 rounded text-gray-900 dark:text-gray-100"></ul>
                </div>
            </div>
            <div class="p-6 border-t dark:border-gray-700 flex justify-end gap-2">
                <button onclick="closeViewModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-gray-100 rounded hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- End of Day Reminder Modal -->
    <div id="endOfDayReminderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full animate-bounce">
            <div class="bg-gradient-to-r from-orange-500 to-red-500 p-6 text-white rounded-t-lg">
                <h3 class="text-2xl font-bold flex items-center gap-2">
                    🌙 End of Day Reminder
                </h3>
                <p class="text-sm mt-2 opacity-90">Don't forget to complete your end-of-day tasks</p>
            </div>

            <div class="p-6">
                <div class="mb-6 space-y-4">
                    <!-- Task 1: Odometer Reading -->
                    <div class="flex items-start gap-3 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                        <div class="text-2xl mt-1">📍</div>
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-gray-100">Log Odometer Reading</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Record your final odometer reading for the day</p>
                        </div>
                    </div>

                    <!-- Task 2: Vehicle Checklist -->
                    <div class="flex items-start gap-3 p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                        <div class="text-2xl mt-1">✓</div>
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-gray-100">Vehicle End-of-Day Checklist</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Check vehicle condition and report any issues</p>
                        </div>
                    </div>

                    <!-- Task 3: Summary -->
                    <div class="flex items-start gap-3 p-4 bg-purple-50 dark:bg-purple-900 rounded-lg">
                        <div class="text-2xl mt-1">📊</div>
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-gray-100">Daily Summary</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Add any notes or issues from today's trips</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button onclick="snoozeReminder()" class="flex-1 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-100 px-4 py-2 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition font-semibold">
                        Snooze
                    </button>
                    <button onclick="completeEndOfDay()" class="flex-1 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white px-4 py-2 rounded-lg transition font-semibold">
                        Start
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- End of Day Notification Toast -->
    <div id="endOfDayToast" class="fixed bottom-4 right-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 max-w-sm hidden z-40 border-l-4 border-orange-500">
        <div class="flex items-center gap-3">
            <div class="text-3xl">🌙</div>
            <div>
                <p class="font-bold text-gray-800 dark:text-gray-100">Time to wrap up!</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Complete your end-of-day checklist</p>
            </div>
            <button onclick="closeToast()" class="ml-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 text-xl">×</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let currentRouteId = null;
        let snoozeTimeout = null;
        const END_OF_DAY_HOUR = 17; // 5 PM
        const SNOOZE_MINUTES = 15;

        // Load routes and reminders on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadRoutes();
            loadTodayReminders();
            loadStatistics();
            checkEndOfDayReminder();

            // Refresh reminders every 5 minutes
            setInterval(loadTodayReminders, 300000);
            setInterval(loadStatistics, 300000);
            
            // Check for end of day reminder every minute
            setInterval(checkEndOfDayReminder, 60000);
        });

        function loadRoutes() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('getUserRoutes') }}", {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(routes => {
                renderRoutes(routes);
            })
            .catch(error => {
                console.error('Error loading routes:', error);
                document.getElementById('routesTableBody').innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-red-500">
                            Error loading routes. Please try again.
                        </td>
                    </tr>
                `;
            });
        }

        function renderRoutes(routes) {
            const tableBody = document.getElementById('routesTableBody');
            const emptyState = document.getElementById('emptyState');

            if (routes.length === 0) {
                tableBody.innerHTML = '';
                emptyState.classList.remove('hidden');
                return;
            }

            emptyState.classList.add('hidden');
            tableBody.innerHTML = routes.map(route => `
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">#${route.id}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold ${route.optimize_type === 'duration' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'}">
                            ${route.optimize_type === 'duration' ? '⏱️ Duration' : '📍 Distance'}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">${route.total_weight}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">${JSON.parse(route.locations).length} locations</td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">${new Date(route.created_at).toLocaleDateString()}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button onclick="viewRoute(${route.id})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-semibold">
                                View
                            </button>
                            <button onclick="deleteRoute(${route.id})" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-semibold">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function viewRoute(routeId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`{{ route('route.show', ':id') }}`.replace(':id', routeId), {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(route => {
                currentRouteId = route.id;
                document.getElementById('modalPath').textContent = route.optimal_path;
                document.getElementById('modalWeight').textContent = route.total_weight;
                document.getElementById('modalOptimizeType').textContent = route.optimize_type === 'duration' ? '⏱️ Duration' : '📍 Distance';
                
                const locationsList = document.getElementById('modalLocations');
                const locations = JSON.parse(route.locations);
                locationsList.innerHTML = locations.map(loc => `<li>${loc}</li>`).join('');

                document.getElementById('viewRouteModal').classList.remove('hidden');
                document.getElementById('viewRouteModal').classList.add('flex');
            })
            .catch(error => {
                console.error('Error loading route:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load route details.'
                });
            });
        }

        function closeViewModal() {
            document.getElementById('viewRouteModal').classList.add('hidden');
            document.getElementById('viewRouteModal').classList.remove('flex');
            currentRouteId = null;
        }

        function deleteRoute(routeId) {
            Swal.fire({
                title: 'Delete Route?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch(`{{ route('route.delete', ':id') }}`.replace(':id', routeId), {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Route deleted successfully.'
                        });
                        loadRoutes();
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete route.'
                        });
                        console.error(error);
                    });
                }
            });
        }

        // Close modal when clicking outside
        document.getElementById('viewRouteModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeViewModal();
            }
        });

        function loadTodayReminders() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route("getTodayReminders") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(reminders => {
                renderReminders(reminders);
            })
            .catch(error => console.error('Error loading reminders:', error));
        }

        function renderReminders(reminders) {
            const container = document.getElementById('remindersContainer');
            
            if (reminders.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center">No reminders for today</p>';
                return;
            }

            container.innerHTML = reminders.map(reminder => `
                <div class="flex items-center justify-between p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg mb-3 ${reminder.is_completed ? 'bg-green-50 dark:bg-green-900' : 'bg-blue-50 dark:bg-blue-900'}">
                    <div class="flex items-center gap-4">
                        <span class="text-2xl">${reminder.icon}</span>
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-gray-100">${reminder.type_label}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">${reminder.notes}</p>
                        </div>
                    </div>
                    <div>
                        ${reminder.is_completed ? 
                            `<span class="text-green-600 dark:text-green-400 font-semibold">✓ Completed</span>` :
                            `<button onclick="completeReminder(${reminder.id})" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition">
                                Mark Complete
                            </button>`
                        }
                    </div>
                </div>
            `).join('');
        }

        function loadStatistics() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route("getReminderStatistics") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(stats => {
                document.getElementById('totalToday').textContent = stats.total_today;
                document.getElementById('completedToday').textContent = stats.completed_today;
                document.getElementById('pendingToday').textContent = stats.pending_today;
                document.getElementById('overdue').textContent = stats.pending_overdue;
            })
            .catch(error => console.error('Error loading statistics:', error));
        }

        function completeReminder(reminderId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            Swal.fire({
                title: 'Mark as Completed?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Complete'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ route('completeReminder', ':id') }}`.replace(':id', reminderId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Done!', 'Reminder marked as completed', 'success');
                            loadTodayReminders();
                            loadStatistics();
                        }
                    });
                }
            });
        }

        // END OF DAY REMINDER FUNCTIONS
        function checkEndOfDayReminder() {
            const now = new Date();
            const currentHour = now.getHours();

            // Show reminder at configured hour (5 PM = 17:00)
            if (currentHour === END_OF_DAY_HOUR) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('{{ route("getTodayReminders") }}', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(reminders => {
                    const endOfDayReminder = reminders.find(r => r.type === 'end_of_day');
                    
                    if (endOfDayReminder && !endOfDayReminder.is_completed) {
                        showEndOfDayReminder();
                    }
                })
                .catch(error => console.error('Error checking reminder:', error));
            }
        }

        function showEndOfDayReminder() {
            // Show modal
            const modal = document.getElementById('endOfDayReminderModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Show toast notification
            const toast = document.getElementById('endOfDayToast');
            toast.classList.remove('hidden');

            // Play notification sound
            playNotificationSound();

            // Request browser notification permission
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification('End of Day Reminder', {
                    body: 'Time to complete your end-of-day checklist!',
                    icon: '🌙',
                    tag: 'end-of-day-reminder',
                    requireInteraction: true
                });
            }

            // Auto-close toast after 10 seconds
            setTimeout(() => {
                closeToast();
            }, 10000);
        }

        function snoozeReminder() {
            const modal = document.getElementById('endOfDayReminderModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            Swal.fire({
                icon: 'info',
                title: 'Reminder Snoozed',
                text: 'We\'ll remind you again in ' + SNOOZE_MINUTES + ' minutes',
                confirmButtonColor: '#667eea'
            });

            clearTimeout(snoozeTimeout);
            snoozeTimeout = setTimeout(() => {
                showEndOfDayReminder();
            }, SNOOZE_MINUTES * 60 * 1000);
        }

        function completeEndOfDay() {
            const modal = document.getElementById('endOfDayReminderModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            Swal.fire({
                title: 'End of Day Tasks',
                html: '<p class="mb-4 font-semibold">What would you like to do?</p>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Go to Logbook',
                confirmButtonColor: '#667eea',
                cancelButtonText: 'I\'ll do it later'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("driver-logbook") }}';
                }
            });
        }

        function closeToast() {
            const toast = document.getElementById('endOfDayToast');
            toast.classList.add('hidden');
        }

        function playNotificationSound() {
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.frequency.value = 800;
                oscillator.type = 'sine';

                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.5);
            } catch (e) {
                console.log('Audio notification not supported');
            }
        }

        // Request notification permission on load
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    </script>

    <style>
        /* Loading Spinner */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* Bounce animation for modal */
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .animate-bounce {
            animation: bounce 1s infinite;
        }

        /* Table Responsive */
        @media (max-width: 768px) {
            table {
                font-size: 0.875rem;
            }

            th, td {
                padding: 0.75rem 0.5rem !important;
            }

            #remindersStats {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }
    </style>
</x-app-layout>