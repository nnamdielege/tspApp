<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Settings Navigation -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden sticky top-24">
                        <div class="p-6 border-b dark:border-gray-700">
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">Settings</h3>
                        </div>
                        <nav class="space-y-2 p-4">
                            <button onclick="showTab('notifications')" class="settings-nav-btn active w-full text-left px-4 py-2 rounded-lg bg-purple-100 dark:bg-purple-900 text-purple-900 dark:text-purple-200 transition">
                                🔔 Notifications
                            </button>
                            <button onclick="showTab('display')" class="settings-nav-btn w-full text-left px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                🎨 Display
                            </button>
                            <button onclick="showTab('privacy')" class="settings-nav-btn w-full text-left px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                🔒 Privacy
                            </button>
                            <button onclick="showTab('preferences')" class="settings-nav-btn w-full text-left px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                ⚙️ Preferences
                            </button>
                            <button onclick="showTab('vehicle')" class="settings-nav-btn w-full text-left px-4 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                🚗 Vehicle
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Settings Content -->
                <div class="lg:col-span-3">
                    <!-- Notifications Tab -->
                    <div id="notifications-tab" class="settings-tab">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                            <div class="p-6 border-b dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">🔔 Notification Settings</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage how and when you receive notifications</p>
                            </div>

                            <div class="p-6 space-y-6">
                                <div class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-700">
                                    <div>
                                        <label class="font-semibold text-gray-900 dark:text-gray-100">Email Notifications</label>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Receive email notifications for important updates</p>
                                    </div>
                                    <input type="checkbox" id="email_notifications" class="w-6 h-6 cursor-pointer">
                                </div>

                                <div class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-700">
                                    <div>
                                        <label class="font-semibold text-gray-900 dark:text-gray-100">End of Day Reminder</label>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Get reminded to complete your end-of-day checklist</p>
                                    </div>
                                    <input type="checkbox" id="end_of_day_reminder" class="w-6 h-6 cursor-pointer">
                                </div>

                                <div class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-700">
                                    <div>
                                        <label class="font-semibold text-gray-900 dark:text-gray-100">Missing Log Alerts</label>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Be notified if you forget to log daily checklist or odometer</p>
                                    </div>
                                    <input type="checkbox" id="missing_log_notification" class="w-6 h-6 cursor-pointer">
                                </div>

                                <div class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-700">
                                    <div>
                                        <label class="font-semibold text-gray-900 dark:text-gray-100">Route Completion Alerts</label>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Notify when route completion is detected</p>
                                    </div>
                                    <input type="checkbox" id="route_completion_notification" class="w-6 h-6 cursor-pointer">
                                </div>

                                <div class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-700">
                                    <div>
                                        <label class="font-semibold text-gray-900 dark:text-gray-100">Daily Summary Email</label>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Receive a daily summary of your activities</p>
                                    </div>
                                    <input type="checkbox" id="daily_summary_email" class="w-6 h-6 cursor-pointer">
                                </div>

                                <div class="p-4 border rounded-lg dark:border-gray-700">
                                    <label class="block font-semibold text-gray-900 dark:text-gray-100 mb-2">Preferred Reminder Time</label>
                                    <input type="time" id="preferred_reminder_time" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                                </div>

                                <div class="p-4 border rounded-lg dark:border-gray-700">
                                    <label class="block font-semibold text-gray-900 dark:text-gray-100 mb-2">Remind Me (Hours Before)</label>
                                    <input type="number" id="reminder_time_hours_before" min="1" max="24" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                                </div>

                                <button onclick="saveSettings('notifications')" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition font-semibold">
                                    Save Settings
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Display Tab -->
                    <div id="display-tab" class="settings-tab hidden">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                            <div class="p-6 border-b dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">🎨 Display Preferences</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Customize how the app looks and feels</p>
                            </div>

                            <div class="p-6 space-y-6">
                                <div class="p-4 border rounded-lg dark:border-gray-700">
                                    <label class="block font-semibold text-gray-900 dark:text-gray-100 mb-3">Theme</label>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="theme" value="light" class="w-4 h-4">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">☀️ Light</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="theme" value="dark" class="w-4 h-4">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">🌙 Dark</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="theme" value="system" class="w-4 h-4">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">🖥️ System</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="p-4 border rounded-lg dark:border-gray-700">
                                    <label class="block font-semibold text-gray-900 dark:text-gray-100 mb-2">Timezone</label>
                                    <select id="timezone" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                                        <option>Loading timezones...</option>
                                    </select>
                                </div>

                                <div class="p-4 border rounded-lg dark:border-gray-700">
                                    <label class="block font-semibold text-gray-900 dark:text-gray-100 mb-2">Language</label>
                                    <select id="language" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
                                        <option value="en">English</option>
                                        <option value="es">Spanish</option>
                                        <option value="fr">French</option>
                                        <option value="de">German</option>
                                    </select>
                                </div>

                                <div class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-700">
                                    <div>
                                        <label class="font-semibold text-gray-900 dark:text-gray-100">24-Hour Format</label>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Use 24-hour time format</p>
                                    </div>
                                    <input type="checkbox" id="24_hour_format" class="w-6 h-6 cursor-pointer">
                                </div>

                                <button onclick="saveSettings('display')" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition font-semibold">
                                    Save Settings
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Privacy Tab -->
                    <div id="privacy-tab" class="settings-tab hidden">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                            <div class="p-6 border-b dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">🔒 Privacy Settings</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Control what data is visible to others</p>
                            </div>

                            <div class="p-6 space-y-6">
                                <div class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-700">
                                    <div>
                                        <label class="font-semibold text-gray-900 dark:text-gray-100">Show Location History</label>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Allow admins to view your route history</p>
                                    </div>
                                    <input type="checkbox" id="show_location_history" class="w-6 h-6 cursor-pointer">
                                </div>

                                <div class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-700">
                                    <div>
                                        <label class="font-semibold text-gray-900 dark:text-gray-100">Show Distance Traveled</label>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Allow admins to view your distance statistics</p>
                                    </div>
                                    <input type="checkbox" id="show_distance_traveled" class="w-6 h-6 cursor-pointer">
                                </div>

                                <div class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-700">
                                    <div>
                                        <label class="font-semibold text-gray-900 dark:text-gray-100">Allow Admin View Logs</label>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Allow admins to view your daily logs</p>
                                    </div>
                                    <input type="checkbox" id="allow_admin_view_logs" class="w-6 h-6 cursor-pointer">
                                </div>

                                <button onclick="saveSettings('privacy')" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition font-semibold">
                                    Save Settings
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Preferences Tab -->
                    <div id="preferences-tab" class="settings-tab hidden">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                            <div class="p-6 border-b dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">⚙️ Route Preferences</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Set your default route behavior</p>
                            </div>

                            <div class="p-6 space-y-6">
                                <div class="p-4 border rounded-lg dark:border-gray-700">
                                    <label class="block font-semibold text-gray-900 dark:text-gray-100 mb-3">Default Optimization Type</label>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="default_optimization" value="duration" class="w-4 h-4">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">⏱️ Duration (Fastest)</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="default_optimization" value="distance" class="w-4 h-4">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">📍 Distance (Shortest)</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-700">
                                    <div>
                                        <label class="font-semibold text-gray-900 dark:text-gray-100">Auto-Start Route</label>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Automatically mark routes as started</p>
                                    </div>
                                    <input type="checkbox" id="auto_start_route" class="w-6 h-6 cursor-pointer">
                                </div>

                                <div class="flex items-center justify-between p-4 border rounded-lg dark:border-gray-700">
                                    <div>
                                        <label class="font-semibold text-gray-900 dark:text-gray-100">Auto-Complete Route</label>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Automatically mark routes as completed</p>
                                    </div>
                                    <input type="checkbox" id="auto_complete_route" class="w-6 h-6 cursor-pointer">
                                </div>

                                <button onclick="saveSettings('preferences')" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition font-semibold">
                                    Save Settings
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Tab -->
                    <div id="vehicle-tab" class="settings-tab hidden">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                            <div class="p-6 border-b dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">🚗 Vehicle Information</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Add information about your vehicle</p>
                            </div>

                            <div class="p-6 space-y-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Vehicle Number/Plate</label>
                                    <input type="text" id="vehicle_number" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100" placeholder="e.g., ABC-1234">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Vehicle Model</label>
                                    <input type="text" id="vehicle_model" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100" placeholder="e.g., Toyota Hiace 2020">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                                    <textarea id="vehicle_notes" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100" rows="4" placeholder="Add any additional vehicle information..."></textarea>
                                </div>

                                <button onclick="saveSettings('vehicle')" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition font-semibold">
                                    Save Vehicle Info
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Reset Settings Button -->
                    <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-yellow-900 dark:text-yellow-200">Reset All Settings</p>
                            <p class="text-sm text-yellow-800 dark:text-yellow-300">Restore all settings to their default values</p>
                        </div>
                        <button onclick="resetAllSettings()" class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition font-semibold">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadSettings();
            loadTimezones();
        });

        function showTab(tabName) {
            document.querySelectorAll('.settings-tab').forEach(tab => {
                tab.classList.add('hidden');
            });

            document.getElementById(tabName + '-tab').classList.remove('hidden');

            document.querySelectorAll('.settings-nav-btn').forEach(btn => {
                btn.classList.remove('bg-purple-100', 'dark:bg-purple-900', 'text-purple-900', 'dark:text-purple-200');
                btn.classList.add('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
            });

            event.target.classList.remove('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
            event.target.classList.add('bg-purple-100', 'dark:bg-purple-900', 'text-purple-900', 'dark:text-purple-200');
        }

        function loadSettings() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route("settings.get") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const settings = data.settings;

                    document.getElementById('email_notifications').checked = settings.email_notifications;
                    document.getElementById('end_of_day_reminder').checked = settings.end_of_day_reminder;
                    document.getElementById('missing_log_notification').checked = settings.missing_log_notification;
                    document.getElementById('route_completion_notification').checked = settings.route_completion_notification;
                    document.getElementById('daily_summary_email').checked = settings.daily_summary_email;
                    document.getElementById('preferred_reminder_time').value = settings.preferred_reminder_time;
                    document.getElementById('reminder_time_hours_before').value = settings.reminder_time_hours_before;

                    document.querySelector('input[name="theme"][value="' + settings.theme + '"]').checked = true;
                    document.getElementById('timezone').value = settings.timezone;
                    document.getElementById('language').value = settings.language;
                    document.getElementById('24_hour_format').checked = settings['24_hour_format'];

                    document.getElementById('show_location_history').checked = settings.show_location_history;
                    document.getElementById('show_distance_traveled').checked = settings.show_distance_traveled;
                    document.getElementById('allow_admin_view_logs').checked = settings.allow_admin_view_logs;

                    document.querySelector('input[name="default_optimization"][value="' + settings.default_optimization + '"]').checked = true;
                    document.getElementById('auto_start_route').checked = settings.auto_start_route;
                    document.getElementById('auto_complete_route').checked = settings.auto_complete_route;

                    document.getElementById('vehicle_number').value = settings.vehicle_number || '';
                    document.getElementById('vehicle_model').value = settings.vehicle_model || '';
                    document.getElementById('vehicle_notes').value = settings.vehicle_notes || '';
                }
            })
            .catch(error => console.error('Error loading settings:', error));
        }

        function loadTimezones() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route("settings.timezones") }}', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(timezones => {
                const select = document.getElementById('timezone');
                select.innerHTML = '';
                timezones.forEach(tz => {
                    const option = document.createElement('option');
                    option.value = tz;
                    option.textContent = tz;
                    select.appendChild(option);
                });
                loadSettings();
            })
            .catch(error => console.error('Error loading timezones:', error));
        }

        function saveSettings(type) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let url, data;

            if (type === 'notifications') {
                url = '{{ route("settings.notifications") }}';
                data = {
                    email_notifications: document.getElementById('email_notifications').checked,
                    end_of_day_reminder: document.getElementById('end_of_day_reminder').checked,
                    missing_log_notification: document.getElementById('missing_log_notification').checked,
                    route_completion_notification: document.getElementById('route_completion_notification').checked,
                    daily_summary_email: document.getElementById('daily_summary_email').checked,
                    preferred_reminder_time: document.getElementById('preferred_reminder_time').value,
                    reminder_time_hours_before: parseInt(document.getElementById('reminder_time_hours_before').value),
                };
            } else if (type === 'display') {
                url = '{{ route("settings.display") }}';
                data = {
                    theme: document.querySelector('input[name="theme"]:checked').value,
                    timezone: document.getElementById('timezone').value,
                    language: document.getElementById('language').value,
                    '24_hour_format': document.getElementById('24_hour_format').checked,
                };
            } else if (type === 'privacy') {
                url = '{{ route("settings.privacy") }}';
                data = {
                    show_location_history: document.getElementById('show_location_history').checked,
                    show_distance_traveled: document.getElementById('show_distance_traveled').checked,
                    allow_admin_view_logs: document.getElementById('allow_admin_view_logs').checked,
                };
            } else if (type === 'preferences') {
                url = '{{ route("settings.preferences") }}';
                data = {
                    default_optimization: document.querySelector('input[name="default_optimization"]:checked').value,
                    auto_start_route: document.getElementById('auto_start_route').checked,
                    auto_complete_route: document.getElementById('auto_complete_route').checked,
                };
            } else if (type === 'vehicle') {
                url = '{{ route("settings.vehicle") }}';
                data = {
                    vehicle_number: document.getElementById('vehicle_number').value,
                    vehicle_model: document.getElementById('vehicle_model').value,
                    vehicle_notes: document.getElementById('vehicle_notes').value,
                };
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success!', data.message, 'success');
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Failed to save settings', 'error');
            });
        }

        function resetAllSettings() {
            Swal.fire({
                title: 'Reset All Settings?',
                text: 'This will restore all settings to their default values.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Reset'
            }).then((result) => {
                if (result.isConfirmed) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch('{{ route("settings.reset") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Success!', data.message, 'success');
                            setTimeout(() => location.reload(), 1500);
                        }
                    });
                }
            });
        }
    </script>

    <style>
        @media (max-width: 1024px) {
            .grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</x-app-layout>