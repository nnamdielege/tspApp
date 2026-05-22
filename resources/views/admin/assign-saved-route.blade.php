<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100 leading-tight">
                Assign Saved Optimal Route
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Assign generated TSP routes to drivers and monitor stop progress.
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">
                    Saved Optimal Routes
                </h3>

                <div class="space-y-6">
                    @forelse($routes as $route)
                        @php
                            $stops = $route->ordered_stops ?? $route->locations ?? [];

                            if (is_string($stops)) {
                                $stops = json_decode($stops, true) ?? [];
                            }

                            $totalStops = is_array($stops) ? count($stops) : 0;
                            $completedStops = collect($stops)->where('status', 'delivered')->count();
                            $arrivedStops = collect($stops)->where('status', 'arrived')->count();
                            $failedStops = collect($stops)->where('status', 'failed')->count();
                            $pendingStops = $totalStops - $completedStops - $arrivedStops - $failedStops;
                        @endphp

                        <div class="border rounded-xl p-5 bg-gray-50 dark:bg-gray-700">
                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4 mb-4">
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-gray-100">
                                        Route #{{ $route->id }}
                                    </h4>

                                    <p class="text-sm text-gray-500 dark:text-gray-300">
                                        Created by: {{ $route->user->name ?? 'Unknown' }}
                                    </p>

                                    <p class="text-sm text-gray-500 dark:text-gray-300">
                                        Stops: {{ $totalStops }}
                                    </p>

                                    <p class="text-sm text-gray-500 dark:text-gray-300">
                                        Total: {{ $route->total_weight }}
                                    </p>

                                    <p class="text-sm text-gray-500 dark:text-gray-300">
                                        Optimise by: {{ ucfirst($route->optimize_type) }}
                                    </p>

                                    <p class="text-sm text-gray-500 dark:text-gray-300">
                                        Route Status: {{ ucfirst($route->status) }}
                                    </p>

                                    @if($route->employee)
                                        <p class="text-sm text-green-600 dark:text-green-300 font-semibold">
                                            Assigned to: {{ $route->employee->name }}
                                        </p>
                                    @else
                                        <p class="text-sm text-red-600 dark:text-red-300 font-semibold">
                                            Not assigned
                                        </p>
                                    @endif
                                </div>

                                <form method="POST" action="{{ route('admin.assign-saved-route.assign', $route->id) }}" style="margin-top: 20px; margin-bottom: 25px;">
                                    @csrf

                                    <label style="display:block; font-weight:bold; margin-bottom:8px;">
                                        Assign Driver
                                    </label>

                                    <select name="employee_id" required style="display:block; width:100%; padding:12px; border:1px solid #999; border-radius:6px; margin-bottom:15px;">
                                        <option value="">Select Driver</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}" @selected($route->employee_id == $driver->id)>
                                                {{ $driver->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="submit"
                                        style="display:block; width:100%; background:#6d28d9; color:white; padding:14px 20px; border:none; border-radius:8px; font-weight:bold; cursor:pointer;">
                                        Save Assignment
                                    </button>
                                </form>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border">
                                    <p class="text-xs text-gray-500">Delivered</p>
                                    <p class="text-xl font-bold text-green-600">{{ $completedStops }}</p>
                                </div>

                                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border">
                                    <p class="text-xs text-gray-500">Arrived</p>
                                    <p class="text-xl font-bold text-blue-600">{{ $arrivedStops }}</p>
                                </div>

                                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border">
                                    <p class="text-xs text-gray-500">Pending</p>
                                    <p class="text-xl font-bold text-gray-600">{{ $pendingStops }}</p>
                                </div>

                                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border">
                                    <p class="text-xs text-gray-500">Failed</p>
                                    <p class="text-xl font-bold text-red-600">{{ $failedStops }}</p>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-3">
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                                        Stops in optimal order
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        Progress: {{ $completedStops }} / {{ $totalStops }} completed
                                    </p>
                                </div>

                                @if(is_array($stops) && count($stops))
                                    <ol class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                                        @foreach($stops as $index => $stop)
                                            @php
                                                $status = $stop['status'] ?? 'pending';

                                                $badgeClass = match($status) {
                                                    'delivered' => 'bg-green-100 text-green-800',
                                                    'arrived' => 'bg-blue-100 text-blue-800',
                                                    'failed' => 'bg-red-100 text-red-800',
                                                    default => 'bg-gray-100 text-gray-800',
                                                };
                                            @endphp

                                            <li class="flex flex-col md:flex-row md:justify-between md:items-start gap-2 border-b pb-3">
                                                <div>
                                                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $index + 1 }}.
                                                        {{ $stop['customer_name'] ?? $stop['name'] ?? $stop['address'] ?? $stop['location'] ?? 'Stop' }}
                                                    </p>

                                                    @if(!empty($stop['address']))
                                                        <p class="text-xs text-gray-500 mt-1">
                                                            {{ $stop['address'] }}
                                                        </p>
                                                    @endif

                                                    @if(!empty($stop['arrived_at']))
                                                        <p class="text-xs text-blue-600 mt-1">
                                                            Arrived: {{ $stop['arrived_at'] }}
                                                        </p>
                                                    @endif

                                                    @if(!empty($stop['delivered_at']))
                                                        <p class="text-xs text-green-600 mt-1">
                                                            Delivered: {{ $stop['delivered_at'] }}
                                                        </p>
                                                    @endif

                                                    @if(!empty($stop['failed_at']))
                                                        <p class="text-xs text-red-600 mt-1">
                                                            Failed: {{ $stop['failed_at'] }}
                                                        </p>
                                                    @endif
                                                </div>

                                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ol>
                                @else
                                    <p class="text-sm text-gray-500">No stops found.</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No saved optimal routes found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        setInterval(() => {
            window.location.reload();
        }, 15000);
    </script>
</x-app-layout>