<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Employee Logs Review') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="totalReminders">0</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Reminders Sent Today</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400" id="acknowledgedReminders">0</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Acknowledged</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400" id="pendingReminders">0</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Pending</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400" id="missingLogs">0</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Missing Logs</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
                <div class="flex gap-4 flex-wrap">
                    <input type="date" id="filterDate" class="px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                    <button onclick="loadEmployeeLogsStatus()" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition">
                        Filter
                    </button>
                    <button onclick="loadMissingLogs()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
                        Show Missing Logs Only
                    </button>
                </div>
            </div>

            <!-- Employee Logs Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Employee Daily Log Status</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700 border-b dark:border-gray-600">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Employee</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Email</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">Checklist</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">Odometer</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">Progress</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="employeeLogsTableBody">
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center">
                                    <div class="flex justify-center items-center">
                                        <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="ml-2">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Reminders History -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mt-6">
                <div class="p-6 border-b dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Reminders Sent</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700 border-b dark:border-gray-600">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Employee</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Reminder Type</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Sent At</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Message</th>
                            </tr>
                        </thead>
                        <tbody id="remindersTableBody">
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">No reminders sent yet</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('filterDate').value = today;
            
            loadEmployeeLogsStatus();
            loadAdminReminders();
            loadReminderStatistics();
        });

        function loadEmployeeLogsStatus() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const date = document.getElementById('filterDate').value;

            fetch(`{{ route('admin.employee-logs.status') }}?date=${date}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(employees => {
                renderEmployeeLogsTable(employees);
            })
            .catch(error => console.error('Error loading employee logs:', error));
        }

        function renderEmployeeLogsTable(employees) {
            const tableBody = document.getElementById('employeeLogsTableBody');
            
            if (employees.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center">No employees found</td></tr>';
                return;
            }

            tableBody.innerHTML = employees.map(emp => `
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">${emp.name}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">${emp.email}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold ${emp.checklist_status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'}">
                            ${emp.checklist_status === 'completed' ? '✓ ' + emp.checklist_time : '⏳ Pending'}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold ${emp.odometer_status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'}">
                            ${emp.odometer_status === 'completed' ? '✓ ' + emp.odometer_distance + ' km' : '⏳ Pending'}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: ${emp.status_percentage}%"></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="viewEmployeeDetails(${emp.id})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-sm font-semibold mr-2">View</button>
                        ${emp.overall_status === 'incomplete' ? `<button onclick="openRemindModal(${emp.id}, '${emp.name}')" class="text-red-600 hover:text-red-800 dark:text-red-400 text-sm font-semibold">Remind</button>` : ''}
                    </td>
                </tr>
            `).join('');
        }

        function loadAdminReminders() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route("admin.reminders") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(reminders => {
                const tableBody = document.getElementById('remindersTableBody');
                if (reminders.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center">No reminders sent yet</td></tr>';
                    return;
                }

                tableBody.innerHTML = reminders.map(r => `
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">${r.employee_name}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">${r.reminder_type.replace('_', ' ')}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">${r.sent_at}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold ${r.acknowledged ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'}">
                                ${r.acknowledged ? '✓ Acknowledged' : 'Pending'}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">${r.notes || '-'}</td>
                    </tr>
                `).join('');
            })
            .catch(error => console.error('Error loading reminders:', error));
        }

        function loadReminderStatistics() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route("admin.reminder-statistics") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(stats => {
                document.getElementById('totalReminders').textContent = stats.total_reminders_sent;
                document.getElementById('acknowledgedReminders').textContent = stats.acknowledged_reminders;
                document.getElementById('pendingReminders').textContent = stats.pending_reminders;
            })
            .catch(error => console.error('Error loading statistics:', error));
        }

        function loadMissingLogs() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const date = document.getElementById('filterDate').value;

            fetch(`{{ route('admin.missing-logs') }}?date=${date}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(employees => {
                document.getElementById('missingLogs').textContent = employees.length;
                renderEmployeeLogsTable(employees);
            })
            .catch(error => console.error('Error loading missing logs:', error));
        }

        function viewEmployeeDetails(employeeId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`{{ route('admin.employee-logs.detailed', ':id') }}`.replace(':id', employeeId), {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(logs => {
                Swal.fire({
                    title: 'Employee Logs',
                    html: logs.length > 0 ? logs.map(log => `<div class="text-left p-2 border-b"><strong>${log.type === 'checklist' ? '✓' : '🛣️'}</strong> ${log.date} ${log.time || ''}</div>`).join('') : 'No logs found',
                    icon: 'info',
                    confirmButtonColor: '#667eea'
                });
            })
            .catch(error => console.error('Error loading employee details:', error));
        }

        function openRemindModal(employeeId, employeeName) {
            Swal.fire({
                title: 'Send Reminder',
                html: `<div class="text-left">
                    <p class="mb-3"><strong>Employee:</strong> ${employeeName}</p>
                    <label class="block mb-2"><strong>Reminder Type:</strong></label>
                    <select id="reminderType" class="w-full p-2 border rounded mb-3">
                        <option value="missing_checklist">Missing Daily Checklist</option>
                        <option value="missing_odometer">Missing Odometer Reading</option>
                    </select>
                    <label class="block mb-2"><strong>Message (Optional):</strong></label>
                    <textarea id="reminderMessage" class="w-full p-2 border rounded" rows="3"></textarea>
                </div>`,
                showCancelButton: true,
                confirmButtonText: 'Send',
                confirmButtonColor: '#ef4444',
                preConfirm: () => {
                    sendReminder(employeeId);
                }
            });
        }

        function sendReminder(employeeId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const reminderType = document.getElementById('reminderType').value;
            const message = document.getElementById('reminderMessage').value;

            fetch('{{ route("admin.send-reminder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    employee_id: employeeId,
                    reminder_type: reminderType,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success!', data.message, 'success');
                    loadAdminReminders();
                }
            })
            .catch(error => console.error('Error sending reminder:', error));
        }
    </script>

    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
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