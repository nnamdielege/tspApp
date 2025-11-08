<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <span class="px-3 py-1 rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 text-sm font-semibold">
                👨‍💼 Admin
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg shadow-lg p-8 mb-8 text-white">
                <h1 class="text-3xl font-bold mb-2">Welcome back, Admin!</h1>
                <p class="text-purple-100">Manage employees, review logs, and monitor system activity</p>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Total Employees</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100" id="totalEmployees">0</p>
                        </div>
                        <div class="text-4xl">👥</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Active Employees</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400" id="activeEmployees">0</p>
                        </div>
                        <div class="text-4xl">✓</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Suspended</p>
                            <p class="text-3xl font-bold text-red-600 dark:text-red-400" id="suspendedEmployees">0</p>
                        </div>
                        <div class="text-4xl">🔒</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Pending Reminders</p>
                            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400" id="pendingReminders">0</p>
                        </div>
                        <div class="text-4xl">📬</div>
                    </div>
                </div>
            </div>

            <!-- Admin Management Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Employee Management Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">👥 Employee Management</h3>
                        <p class="text-blue-100">Manage employees, suspend/reinstate accounts</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <p class="text-gray-600 dark:text-gray-400">
                            View all employees, manage suspensions, and review suspension history.
                        </p>
                        <a href="{{ route('admin.employees') }}" class="inline-block w-full text-center bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition font-semibold">
                            Go to Employee Management
                        </a>
                    </div>
                </div>

                <!-- Employee Logs Review Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">📋 Employee Logs Review</h3>
                        <p class="text-green-100">Monitor daily logs and send reminders</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <p class="text-gray-600 dark:text-gray-400">
                            Review daily checklists, odometer readings, and send reminders to employees.
                        </p>
                        <a href="{{ route('admin.employee-logs') }}" class="inline-block w-full text-center bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition font-semibold">
                            View Employee Logs
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mt-8">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('admin.employees') }}" class="p-4 bg-blue-50 dark:bg-blue-900 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition border border-blue-200 dark:border-blue-700">
                        <p class="font-semibold text-blue-900 dark:text-blue-200">📊 All Employees</p>
                        <p class="text-sm text-blue-700 dark:text-blue-300">View employee list</p>
                    </a>
                    
                    <a href="{{ route('admin.employee-logs') }}" class="p-4 bg-green-50 dark:bg-green-900 rounded-lg hover:bg-green-100 dark:hover:bg-green-800 transition border border-green-200 dark:border-green-700">
                        <p class="font-semibold text-green-900 dark:text-green-200">📋 Daily Logs</p>
                        <p class="text-sm text-green-700 dark:text-green-300">Review employee logs</p>
                    </a>

                    <a href="{{ route('admin.employee-logs') }}?status=incomplete" class="p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-800 transition border border-yellow-200 dark:border-yellow-700">
                        <p class="font-semibold text-yellow-900 dark:text-yellow-200">⚠️ Missing Logs</p>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300">Find incomplete logs</p>
                    </a>

                    <a href="{{ route('admin.employees') }}?status=suspended" class="p-4 bg-red-50 dark:bg-red-900 rounded-lg hover:bg-red-100 dark:hover:bg-red-800 transition border border-red-200 dark:border-red-700">
                        <p class="font-semibold text-red-900 dark:text-red-200">🔒 Suspended Users</p>
                        <p class="text-sm text-red-700 dark:text-red-300">View suspended employees</p>
                    </a>
                </div>
            </div>

            <!-- System Information -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mt-8 border-l-4 border-purple-500">
                <h3 class="font-bold text-gray-900 dark:text-gray-100 mb-3">ℹ️ Admin Information</h3>
                <ul class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                    <li>✓ You have full access to employee management and log review systems</li>
                    <li>✓ Suspended employees cannot access the application</li>
                    <li>✓ All admin actions are logged for audit purposes</li>
                    <li>✓ Use Employee Management to suspend/reinstate employees</li>
                    <li>✓ Use Employee Logs to review daily activity and send reminders</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardStats();
        });

        function loadDashboardStats() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Load suspension statistics
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
            })
            .catch(error => console.error('Error loading stats:', error));

            // Load reminder statistics
            fetch('{{ route("admin.reminder-statistics") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(stats => {
                document.getElementById('pendingReminders').textContent = stats.pending_reminders;
            })
            .catch(error => console.error('Error loading reminders:', error));
        }
    </script>
</x-app-layout>