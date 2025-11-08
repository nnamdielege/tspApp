<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Driver Logbook') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tabs Navigation -->
            <div class="mb-6 flex gap-4 flex-wrap">
                <button onclick="showTab('daily-checklist')" class="tab-btn active px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg transition">
                    Daily Checklist
                </button>
                <button onclick="showTab('odometer-log')" class="tab-btn px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                    Odometer Log
                </button>
                <button onclick="showTab('logs-history')" class="tab-btn px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                    History
                </button>
            </div>

            <!-- Daily Checklist Tab -->
            <div id="daily-checklist" class="tab-content">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-2xl font-bold mb-6">Daily Vehicle Checklist</h3>
                        
                        <form id="checklistForm">
                            <!-- Check Type -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Check Type</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="check_type" value="start_of_day" checked class="w-4 h-4">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Start of Day</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="check_type" value="end_of_day" class="w-4 h-4">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">End of Day</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Odometer Reading -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Odometer Reading (km)</label>
                                <input type="number" name="odometer_reading" class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:border-purple-500 focus:outline-none" placeholder="Enter odometer reading" required>
                            </div>

                            <!-- Checklist Items -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Vehicle Condition</label>
                                <div class="space-y-3">
                                    <label class="flex items-center p-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" name="checklist" value="tires_condition" class="w-5 h-5 text-purple-500">
                                        <span class="ml-3 text-gray-700 dark:text-gray-300">✓ Tires Condition</span>
                                    </label>
                                    <label class="flex items-center p-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" name="checklist" value="brakes" class="w-5 h-5 text-purple-500">
                                        <span class="ml-3 text-gray-700 dark:text-gray-300">✓ Brakes Working</span>
                                    </label>
                                    <label class="flex items-center p-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" name="checklist" value="lights" class="w-5 h-5 text-purple-500">
                                        <span class="ml-3 text-gray-700 dark:text-gray-300">✓ Lights & Signals</span>
                                    </label>
                                    <label class="flex items-center p-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" name="checklist" value="wipers" class="w-5 h-5 text-purple-500">
                                        <span class="ml-3 text-gray-700 dark:text-gray-300">✓ Wipers & Washers</span>
                                    </label>
                                    <label class="flex items-center p-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" name="checklist" value="fuel" class="w-5 h-5 text-purple-500">
                                        <span class="ml-3 text-gray-700 dark:text-gray-300">✓ Fuel Level</span>
                                    </label>
                                    <label class="flex items-center p-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" name="checklist" value="interior_cleanliness" class="w-5 h-5 text-purple-500">
                                        <span class="ml-3 text-gray-700 dark:text-gray-300">✓ Interior Cleanliness</span>
                                    </label>
                                    <label class="flex items-center p-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" name="checklist" value="documents" class="w-5 h-5 text-purple-500">
                                        <span class="ml-3 text-gray-700 dark:text-gray-300">✓ Documents & Insurance</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Issues/Notes -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Any Issues or Notes</label>
                                <textarea name="notes" class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:border-purple-500 focus:outline-none" rows="4" placeholder="Report any issues or maintenance needed..."></textarea>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                                Submit Checklist
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Odometer Log Tab -->
            <div id="odometer-log" class="tab-content hidden">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-2xl font-bold mb-6">Odometer Reading - Travel & Maintenance</h3>
                        
                        <form id="odometerForm">
                            <!-- Date -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Date</label>
                                <input type="date" name="date" class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:border-purple-500 focus:outline-none" required>
                            </div>

                            <!-- Starting Odometer -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Starting Odometer (km)</label>
                                <input type="number" name="starting_odometer" class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:border-purple-500 focus:outline-none" placeholder="Enter starting reading" required>
                            </div>

                            <!-- Ending Odometer -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Ending Odometer (km)</label>
                                <input type="number" name="ending_odometer" class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:border-purple-500 focus:outline-none" placeholder="Enter ending reading" required>
                            </div>

                            <!-- Log Type -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Log Type</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="log_type" value="travel" checked class="w-4 h-4">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Travel</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="log_type" value="maintenance" class="w-4 h-4">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Maintenance</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Route/Purpose -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Route/Purpose</label>
                                <input type="text" name="purpose" class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:border-purple-500 focus:outline-none" placeholder="e.g., Client Visit, Service Center">
                            </div>

                            <!-- Fuel Used (Maintenance Only) -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Maintenance Cost (Optional)</label>
                                <input type="number" name="maintenance_cost" class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:border-purple-500 focus:outline-none" placeholder="Enter cost if applicable" step="0.01">
                            </div>

                            <!-- Description -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                <textarea name="description" class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:border-purple-500 focus:outline-none" rows="4" placeholder="Add details about the journey or maintenance..."></textarea>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                                Save Odometer Reading
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- History Tab -->
            <div id="logs-history" class="tab-content hidden">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-2xl font-bold mb-6">Logs History</h3>
                        
                        <div class="mb-6 flex gap-2 flex-wrap">
                            <button onclick="filterLogs('all')" class="filter-btn active px-4 py-2 bg-purple-500 text-white rounded-lg transition">All</button>
                            <button onclick="filterLogs('checklist')" class="filter-btn px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition">Checklists</button>
                            <button onclick="filterLogs('odometer')" class="filter-btn px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition">Odometer</button>
                        </div>

                        <!-- Logs Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full border-collapse">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-700 border-b dark:border-gray-600">
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Date</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Type</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Details</th>
                                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="logsTableBody">
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                            <div class="flex justify-center items-center">
                                                <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span class="ml-2">Loading logs...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let currentFilter = 'all';

        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });
            document.getElementById(tabName).classList.remove('hidden');

            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-gradient-to-r', 'from-purple-500', 'to-pink-500', 'text-white');
                btn.classList.add('bg-gray-300', 'dark:bg-gray-600', 'text-gray-800', 'dark:text-gray-100');
            });
            event.target.classList.remove('bg-gray-300', 'dark:bg-gray-600', 'text-gray-800', 'dark:text-gray-100');
            event.target.classList.add('bg-gradient-to-r', 'from-purple-500', 'to-pink-500', 'text-white');

            if (tabName === 'logs-history') {
                loadLogs();
            }
        }

        // Daily Checklist Form
        document.getElementById('checklistForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const checklistItems = Array.from(document.querySelectorAll('input[name="checklist"]:checked')).map(el => el.value);

            const data = {
                check_type: document.querySelector('input[name="check_type"]:checked').value,
                odometer_reading: document.querySelector('input[name="odometer_reading"]').value,
                checklist_items: checklistItems,
                notes: document.querySelector('textarea[name="notes"]').value
            };

            Swal.fire({
                title: 'Saving Checklist...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('{{ route("saveDriverChecklist") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                Swal.fire({
                    icon: 'success',
                    title: 'Checklist Saved!',
                    text: 'Your daily checklist has been recorded.'
                });
                document.getElementById('checklistForm').reset();
            })
            .catch(error => {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to save checklist: ' + error.message
                });
            });
        });

        // Odometer Form
        document.getElementById('odometerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const data = {
                date: document.querySelector('input[name="date"]').value,
                starting_odometer: document.querySelector('input[name="starting_odometer"]').value,
                ending_odometer: document.querySelector('input[name="ending_odometer"]').value,
                log_type: document.querySelector('input[name="log_type"]:checked').value,
                purpose: document.querySelector('input[name="purpose"]').value,
                maintenance_cost: document.querySelector('input[name="maintenance_cost"]').value || null,
                description: document.querySelector('textarea[name="description"]').value
            };

            Swal.fire({
                title: 'Saving Odometer Reading...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch('{{ route("saveOdometerReading") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                Swal.fire({
                    icon: 'success',
                    title: 'Odometer Reading Saved!',
                    text: 'Your odometer reading has been recorded.'
                });
                document.getElementById('odometerForm').reset();
                document.querySelector('input[name="date"]').valueAsDate = new Date();
            })
            .catch(error => {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to save odometer reading: ' + error.message
                });
            });
        });

        function loadLogs() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route("getDriverLogs") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(logs => {
                renderLogs(logs);
            })
            .catch(error => {
                console.error('Error loading logs:', error);
            });
        }

        function renderLogs(logs) {
            const tableBody = document.getElementById('logsTableBody');
            
            if (logs.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">No logs found</td></tr>';
                return;
            }

            tableBody.innerHTML = logs.map(log => `
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">${new Date(log.created_at).toLocaleDateString()}</td>
                    <td class="px-6 py-4 text-sm"><span class="px-3 py-1 rounded-full text-xs font-semibold ${log.type === 'checklist' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'}">${log.type === 'checklist' ? '✓ Checklist' : '🛣️ Odometer'}</span></td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">${log.details}</td>
                    <td class="px-6 py-4 text-center"><button onclick="deleteLog(${log.id})" class="text-red-600 hover:text-red-800 dark:text-red-400 text-sm font-semibold">Delete</button></td>
                </tr>
            `).join('');
        }

        function filterLogs(type) {
            currentFilter = type;
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-purple-500', 'text-white');
                btn.classList.add('bg-gray-300', 'dark:bg-gray-600', 'text-gray-800', 'dark:text-gray-100');
            });
            event.target.classList.remove('bg-gray-300', 'dark:bg-gray-600', 'text-gray-800', 'dark:text-gray-100');
            event.target.classList.add('bg-purple-500', 'text-white');
            loadLogs();
        }

        function deleteLog(logId) {
            Swal.fire({
                title: 'Delete Log?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    fetch('{{ route("deleteDriverLog", ":id") }}'.replace(':id', logId), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire('Deleted!', 'Log deleted successfully.', 'success');
                        loadLogs();
                    });
                }
            });
        }

        // Set today's date as default
        document.querySelector('input[name="date"]').valueAsDate = new Date();
    </script>
</x-app-layout>