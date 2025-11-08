<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Employee Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="totalEmployees">0</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Employees</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400" id="activeEmployees">0</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Active</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400" id="suspendedEmployees">0</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Suspended</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400" id="suspensionPercentage">0%</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Suspension Rate</div>
                </div>
            </div>

            <!-- Employee Management Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Employees</h3>
                        <button onclick="loadEmployees('all')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition text-sm">
                            Refresh
                        </button>
                    </div>

                    <!-- Filter Tabs -->
                    <div class="flex gap-2 flex-wrap">
                        <button onclick="filterEmployees('all')" class="filter-btn active px-4 py-2 bg-blue-500 text-white rounded-lg transition text-sm">
                            All Employees
                        </button>
                        <button onclick="filterEmployees('active')" class="filter-btn px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition text-sm">
                            Active Only
                        </button>
                        <button onclick="filterEmployees('suspended')" class="filter-btn px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition text-sm">
                            Suspended Only
                        </button>
                    </div>
                </div>

                <!-- Employees Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700 border-b dark:border-gray-600">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Email</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">Role</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">Status</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">Joined</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-200">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="employeesTableBody">
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
        </div>
    </div>

    <!-- Suspend Employee Modal -->
    <div id="suspendModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6 border-b dark:border-gray-700 flex justify-between items-center bg-red-50 dark:bg-red-900">
                <h3 class="text-xl font-semibold text-red-900 dark:text-red-200">Suspend Employee</h3>
                <button onclick="closeSuspendModal()" class="text-red-500 hover:text-red-700 dark:hover:text-red-300 text-2xl">&times;</button>
            </div>

            <div class="p-6">
                <div class="mb-4 p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg border border-yellow-200 dark:border-yellow-700">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        ⚠️ Suspending an employee will prevent them from accessing the app and logging any data.
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Employee</label>
                    <p class="text-gray-900 dark:text-gray-100 font-bold" id="modalEmployeeName"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Suspension Reason *</label>
                    <textarea id="suspensionReason" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100" rows="4" placeholder="Please provide a detailed reason for suspension..." required></textarea>
                </div>

                <div class="flex gap-2">
                    <button onclick="closeSuspendModal()" class="flex-1 px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-gray-100 rounded hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                        Cancel
                    </button>
                    <button onclick="confirmSuspend()" class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded transition font-semibold">
                        Suspend
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Suspension History Modal -->
    <div id="historyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6 border-b dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Suspension History</h3>
                <button onclick="closeHistoryModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 text-2xl">&times;</button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Employee</label>
                    <p class="text-gray-900 dark:text-gray-100" id="historyEmployeeName"></p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Current Status</label>
                    <p class="text-gray-900 dark:text-gray-100" id="historyStatus"></p>
                </div>

                <div id="suspensionDetailsContainer" class="hidden space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Suspended On</label>
                        <p class="text-gray-900 dark:text-gray-100" id="suspensionStart"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Suspended By</label>
                        <p class="text-gray-900 dark:text-gray-100" id="suspendedBy"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Reason</label>
                        <p class="text-gray-900 dark:text-gray-100 text-sm bg-red-50 dark:bg-red-900 p-3 rounded" id="suspensionReason"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Duration</label>
                        <p class="text-gray-900 dark:text-gray-100" id="suspensionDuration"></p>
                    </div>

                    <div id="unsuspendedContainer" class="hidden">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Reinstated On</label>
                        <p class="text-gray-900 dark:text-gray-100" id="unsuspendedAt"></p>
                    </div>
                </div>

                <div id="notSuspendedContainer" class="hidden p-4 bg-green-50 dark:bg-green-900 rounded">
                    <p class="text-sm text-green-800 dark:text-green-200">This employee has never been suspended.</p>
                </div>
            </div>

            <div class="p-6 border-t dark:border-gray-700 flex justify-end gap-2">
                <button onclick="closeHistoryModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-gray-100 rounded hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let currentEmployeeId = null;
        let currentFilter = 'all';

        document.addEventListener('DOMContentLoaded', function() {
            loadEmployees('all');
            loadStatistics();
        });

        function loadEmployees(status = 'all') {
            currentFilter = status;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`{{ route('admin.api.employees') }}?status=${status}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(employees => {
                renderEmployeesTable(employees);
                updateFilterButtons(status);
            })
            .catch(error => {
                console.error('Error loading employees:', error);
                document.getElementById('employeesTableBody').innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-red-500">Error loading employees</td></tr>';
            });
        }

        function renderEmployeesTable(employees) {
            const tableBody = document.getElementById('employeesTableBody');

            if (employees.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">No employees found</td></tr>';
                return;
            }

            tableBody.innerHTML = employees.map(emp => `
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">${emp.name}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">${emp.email}</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-900 dark:text-gray-100">${emp.role}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold ${emp.is_suspended ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'}">
                            ${emp.is_suspended ? '🔒 Suspended' : '✓ Active'}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm text-gray-600 dark:text-gray-400">${emp.created_at}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="/admin/employees/${emp.id}/routes-view" class="text-green-600 hover:text-green-800 dark:text-green-400 text-sm font-semibold">
                                Routes
                            </a>
                            <button onclick="viewHistory(${emp.id}, '${emp.name}')" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-sm font-semibold">
                                History
                            </button>
                            ${emp.is_suspended ? 
                                `<button onclick="openUnsuspendConfirm(${emp.id}, '${emp.name}')" class="text-green-600 hover:text-green-800 dark:text-green-400 text-sm font-semibold">
                                    Reinstate
                                </button>` :
                                `<button onclick="openSuspendModal(${emp.id}, '${emp.name}')" class="text-red-600 hover:text-red-800 dark:text-red-400 text-sm font-semibold">
                                    Suspend
                                </button>`
                            }
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function filterEmployees(status) {
            loadEmployees(status);
        }

        function updateFilterButtons(status) {
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-blue-500', 'text-white');
                btn.classList.add('bg-gray-300', 'dark:bg-gray-600', 'text-gray-800', 'dark:text-gray-100');
            });

            // Find the button that matches the current status
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => {
                const btnStatus = btn.textContent.toLowerCase();
                if (
                    (status === 'all' && btnStatus.includes('all')) ||
                    (status === 'active' && btnStatus.includes('active')) ||
                    (status === 'suspended' && btnStatus.includes('suspended'))
                ) {
                    btn.classList.remove('bg-gray-300', 'dark:bg-gray-600', 'text-gray-800', 'dark:text-gray-100');
                    btn.classList.add('bg-blue-500', 'text-white');
                }
            });
        }

        function loadStatistics() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route("admin.suspension-statistics") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(stats => {
                document.getElementById('totalEmployees').textContent = stats.total_employees;
                document.getElementById('activeEmployees').textContent = stats.active_employees;
                document.getElementById('suspendedEmployees').textContent = stats.suspended_employees;
                document.getElementById('suspensionPercentage').textContent = stats.suspension_percentage + '%';
            })
            .catch(error => console.error('Error loading statistics:', error));
        }

        function openSuspendModal(employeeId, employeeName) {
            currentEmployeeId = employeeId;
            document.getElementById('modalEmployeeName').textContent = employeeName;
            document.getElementById('suspensionReason').value = '';
            document.getElementById('suspendModal').classList.remove('hidden');
            document.getElementById('suspendModal').classList.add('flex');
        }

        function closeSuspendModal() {
            document.getElementById('suspendModal').classList.add('hidden');
            document.getElementById('suspendModal').classList.remove('flex');
        }

        function confirmSuspend() {
            const reason = document.getElementById('suspensionReason').value.trim();

            if (!reason) {
                Swal.fire('Error', 'Please provide a suspension reason', 'error');
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            Swal.fire({
                title: 'Confirm Suspension?',
                text: 'This employee will be immediately logged out and unable to access the app.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Suspend'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route("admin.suspend-employee") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            employee_id: currentEmployeeId,
                            reason: reason
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Suspended!', data.message, 'success');
                            closeSuspendModal();
                            loadEmployees(currentFilter);
                            loadStatistics();
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Failed to suspend employee', 'error');
                    });
                }
            });
        }

        function openUnsuspendConfirm(employeeId, employeeName) {
            currentEmployeeId = employeeId;

            Swal.fire({
                title: 'Reinstate Employee?',
                text: employeeName + ' will regain access to the app.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Reinstate'
            }).then((result) => {
                if (result.isConfirmed) {
                    unsuspendEmployee();
                }
            });
        }

        function unsuspendEmployee() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route("admin.unsuspend-employee") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    employee_id: currentEmployeeId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Reinstated!', data.message, 'success');
                    loadEmployees(currentFilter);
                    loadStatistics();
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Failed to reinstate employee', 'error');
            });
        }

        function viewHistory(employeeId, employeeName) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`{{ route('admin.suspension-history', ':id') }}`.replace(':id', employeeId), {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(history => {
                document.getElementById('historyEmployeeName').textContent = employeeName;
                document.getElementById('historyStatus').innerHTML = history.is_currently_suspended
                    ? '<span class="text-red-600 dark:text-red-400 font-semibold">🔒 Currently Suspended</span>'
                    : '<span class="text-green-600 dark:text-green-400 font-semibold">✓ Active</span>';

                if (history.is_currently_suspended || history.suspension_start) {
                    document.getElementById('suspensionDetailsContainer').classList.remove('hidden');
                    document.getElementById('notSuspendedContainer').classList.add('hidden');
                    
                    document.getElementById('suspensionStart').textContent = history.suspension_start;
                    document.getElementById('suspendedBy').textContent = history.suspended_by;
                    document.getElementById('suspensionReason').textContent = history.suspension_reason;
                    document.getElementById('suspensionDuration').textContent = history.suspension_duration;

                    if (history.unsuspended_at) {
                        document.getElementById('unsuspendedContainer').classList.remove('hidden');
                        document.getElementById('unsuspendedAt').textContent = history.unsuspended_at;
                    } else {
                        document.getElementById('unsuspendedContainer').classList.add('hidden');
                    }
                } else {
                    document.getElementById('suspensionDetailsContainer').classList.add('hidden');
                    document.getElementById('notSuspendedContainer').classList.remove('hidden');
                }

                document.getElementById('historyModal').classList.remove('hidden');
                document.getElementById('historyModal').classList.add('flex');
            })
            .catch(error => console.error('Error loading history:', error));
        }

        function closeHistoryModal() {
            document.getElementById('historyModal').classList.add('hidden');
            document.getElementById('historyModal').classList.remove('flex');
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