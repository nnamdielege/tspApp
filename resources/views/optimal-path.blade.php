<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Locate Optimal Path') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Optimal Path Form -->
                    <div class="card-content" style="max-width: 600px; margin: 0 auto;">
                        <h5 class="card-title">Find Your Optimal Route</h5>
                        <form id="pathForm">
                            <!-- Number of Stops -->
                            <div class="form-group">
                                <label class="form-label">Number of Stops</label>
                                <input id="paths" class="form-control" type="number" min="3" max="20" 
                                       placeholder="Enter number (3-20)" required>
                                <div class="info-badge">Minimum 3 stops required</div>
                            </div>

                            <!-- Optimization Type -->
                            <div class="form-group">
                                <label class="form-label">Optimize Route For</label>
                                <select id="optimize" class="form-control" required>
                                    <option value="" disabled selected>Choose optimization criteria</option>
                                    <option value="duration">⏱️ Duration (Fastest)</option>
                                    <option value="distance">📍 Distance (Shortest)</option>
                                </select>
                            </div>

                            <div class="divider"></div>

                            <!-- Locations Container -->
                            <div class="location-inputs-container" id="locationInputsContainer">
                                <label class="form-label" style="margin-bottom: 0.9375rem;">📍 Your Locations</label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Calculate Optimal Path</button>
                        </form>
                    </div>
                    <!-- End Form -->

                </div>
            </div>
        </div>
    </div>

    <style>
        /* Form Styling */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.625rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .dark .form-label {
            color: #e5e7eb;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.375rem;
            font-size: 0.9375rem;
            transition: all 0.3s ease;
            font-family: inherit;
            color: #1f2937;
            background-color: white;
        }

        .dark .form-control {
            background-color: #374151;
            border-color: #4b5563;
            color: #f3f4f6;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .input-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .input-group .form-control {
            flex: 1;
            margin-bottom: 0;
        }

        .input-number {
            background: #f3f4f6;
            width: 2.25rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
            font-weight: 600;
            color: #667eea;
            font-size: 0.8125rem;
            flex-shrink: 0;
        }

        .dark .input-number {
            background: #4b5563;
        }

        .location-inputs-container {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease;
        }

        .location-inputs-container.active {
            max-height: 800px;
        }

        .location-input-wrapper {
            animation: slideIn 0.3s ease-out forwards;
            opacity: 0;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-0.625rem);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn {
            width: 100%;
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-0.125rem);
            box-shadow: 0 10px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .info-badge {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .dark .info-badge {
            background: #1e3a8a;
            color: #93c5fd;
        }

        .divider {
            height: 1px;
            background: #f0f0f0;
            margin: 1.875rem 0;
        }

        .dark .divider {
            background: #4b5563;
        }

        .card-content {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        .dark .card-content {
            background: #374151;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1.5rem;
        }

        .dark .card-title {
            color: #f3f4f6;
        }
    </style>

    <!-- Google Maps API Script -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.api_key') }}&libraries=places"></script>
    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function initialize() {
            const pathsInput = document.getElementById('paths');
            const locationContainer = document.getElementById('locationInputsContainer');
            const optimizeSelect = document.getElementById('optimize');

            pathsInput.addEventListener('input', function() {
                const numOfPaths = parseInt(pathsInput.value);
                
                locationContainer.innerHTML = '<label class="form-label" style="margin-bottom: 0.9375rem;">📍 Your Locations</label>';

                if (numOfPaths >= 3 && numOfPaths <= 20) {
                    locationContainer.classList.add('active');

                    for (let i = 1; i <= numOfPaths; i++) {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'location-input-wrapper';
                        wrapper.style.animationDelay = (i * 50) + 'ms';

                        const inputGroup = document.createElement('div');
                        inputGroup.className = 'input-group';

                        const badge = document.createElement('div');
                        badge.className = 'input-number';
                        badge.textContent = i;

                        const input = document.createElement('input');
                        input.id = 'input' + i;
                        input.className = 'form-control location-input';
                        input.type = 'text';
                        input.placeholder = 'Location ' + i;
                        input.required = true;

                        inputGroup.appendChild(badge);
                        inputGroup.appendChild(input);
                        wrapper.appendChild(inputGroup);
                        locationContainer.appendChild(wrapper);

                        if (typeof google !== 'undefined') {
                            const autocomplete = new google.maps.places.Autocomplete(input);
                            autocomplete.addListener('place_changed', function() {
                                const place = autocomplete.getPlace();
                            });
                        }
                    }
                } else if (numOfPaths > 20) {
                    pathsInput.value = 20;
                    pathsInput.dispatchEvent(new Event('input'));
                } else {
                    locationContainer.classList.remove('active');
                }
            });

            document.getElementById('pathForm').addEventListener('submit', function(event) {
                event.preventDefault();

                const numOfPaths = parseInt(pathsInput.value);
                const locations = [];
                
                for (let i = 1; i <= numOfPaths; i++) {
                    const location = document.getElementById('input' + i).value.trim();
                    if (!location) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Incomplete Form',
                            text: 'Please enter a location for stop ' + i
                        });
                        return;
                    }
                    locations.push(location);
                }

                const optimize = optimizeSelect.value;
                sendToBackend(locations, optimize);
            });
        }

        function sendToBackend(locations, optimize) {
            const data = { locations: locations, optimize: optimize };
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            Swal.fire({
                title: 'Calculating Optimal Route...',
                html: 'Analyzing your locations for the best path.',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch("{{ route('deriveTSP') }}", {
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
                    title: 'Route Optimized!',
                    html: '<div style="text-align: left;"><p><strong>Optimal Path:</strong></p><p style="background: #f3f4f6; padding: 1rem; border-radius: 0.375rem; margin: 0.625rem 0; color: #1f2937;">' + data.optimalPath + '</p><p><strong>Total ' + (data.optimize === 'duration' ? 'Duration' : 'Distance') + ':</strong> <span style="color: #667eea; font-weight: bold; font-size: 1rem;">' + data.totalWeight + '</span></p></div>',
                    confirmButtonColor: '#667eea',
                    confirmButtonText: 'Save Route',
                    showCancelButton: true,
                    cancelButtonText: 'Close'
                }).then((result) => {
                    if (result.isConfirmed) {
                        saveRouteToDatabase(data, optimize);
                    }
                });
            })
            .catch(error => {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Calculation Failed',
                    text: error.message || 'An error occurred while calculating the optimal path.'
                });
                console.error(error);
            });
        }

        function saveRouteToDatabase(routeData, optimizeType) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            Swal.fire({
                title: 'Saving Route...',
                html: 'Please wait while we save your optimized route.',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            const numOfPaths = parseInt(document.getElementById('paths').value);
            const locations = [];
            for (let i = 1; i <= numOfPaths; i++) {
                locations.push(document.getElementById('input' + i).value);
            }

            const saveData = {
                optimalPath: routeData.optimalPath,
                totalWeight: routeData.totalWeight,
                optimize: optimizeType,
                locations: locations
            };

            console.log('Saving data:', saveData);

            fetch("{{ route('saveRoute') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(saveData)
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Route Saved!',
                        text: 'Your optimized route has been saved successfully.',
                        confirmButtonColor: '#667eea'
                    });
                    document.getElementById('pathForm').reset();
                    document.getElementById('locationInputsContainer').innerHTML = '<label class="form-label" style="margin-bottom: 0.9375rem;">📍 Your Locations</label>';
                    document.getElementById('locationInputsContainer').classList.remove('active');
                } else {
                    throw new Error(data.message || 'Failed to save route');
                }
            })
            .catch(error => {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Save Failed',
                    text: 'An error occurred while saving the route: ' + error.message
                });
                console.error(error);
            });
        }

        document.addEventListener('DOMContentLoaded', initialize);
    </script>
</x-app-layout>