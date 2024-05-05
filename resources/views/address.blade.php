<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Optimal Path based on TSP</title>
    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    {{-- @vite('resources/css/app.css') --}}
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.3/sweetalert2.min.css">
    <style>
        .location-input {
            margin-bottom: 10px; /* Add space below each input */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Locate Optimal Path</h5>
                        <form id="pathForm">
                            <div class="form-group mb-2">
                                <label class="form-label">Number of stops</label>
                                <input id="paths" class="form-control" min="3" type="number" placeholder="Enter the number of stops" required>                           
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label">Optimise on:</label>
                                <select id="optimize" class="form-control" type="text" required>
                                    <option value="">Select</option>
                                    <option value="duration">Duration</option>
                                    <option value="distance">Distance</option>
                                </select>                            
                            </div>
                            <div class="form-group mb-2" id="locationInputs">
                                <label class="form-label">Starting Location</label>
                                <input id="input1" class="form-control location-input" type="text" placeholder="Enter your address" required>
                            </div>
                            <!-- Add Submit Button -->
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Google Maps API Script -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.api_key') }}&libraries=places"></script>
    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        google.maps.event.addDomListener(window, 'load', initialize);

        function initialize() {
            var pathsInput = document.getElementById('paths');
            var locationInputs = document.getElementById('locationInputs');

            pathsInput.addEventListener('input', function() {
                var numOfPaths = parseInt(pathsInput.value);
                locationInputs.innerHTML = ''; // Clear existing inputs
                for (var i = 1; i <= numOfPaths; i++) {
                    var input = document.createElement('input');
                    input.id = 'input' + i;
                    input.className = 'form-control location-input';
                    input.type = 'text';
                    input.placeholder = 'Enter your address for Location ' + i;
                    input.required = true;
                    locationInputs.appendChild(input);
                }
                // Initialize autocomplete for new inputs
                for (var j = 1; j <= numOfPaths; j++) {
                    var inputId = 'input' + j;
                    var inputElement = document.getElementById(inputId);
                    var autocomplete = new google.maps.places.Autocomplete(inputElement);
                    autocomplete.addListener('place_changed', function() {
                        var place = autocomplete.getPlace();
                    });
                }
            });

            // Add event listener for form submission
            document.getElementById('pathForm').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                // Gather form data
                var numOfPaths = parseInt(pathsInput.value);
                var locations = [];
                for (var i = 1; i <= numOfPaths; i++) {
                    var inputId = 'input' + i;
                    var inputValue = document.getElementById(inputId).value;
                    locations.push(inputValue);
                }
                var optimize = document.getElementById('optimize').value;

                // Send data to Laravel route
                sendToBackend(locations, optimize);
            });
        }

        // Function to send data to Laravel route
        function sendToBackend(locations, optimize) {
            // console.log(optimize);
            // Construct the data object
            var data = {
                locations: locations,
                optimize: optimize
            };

            // Show loading Swal
            Swal.fire({
                title: 'Processing...',
                html: 'Please wait while we calculate the optimal path.',
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send data to Laravel route using fetch API
            fetch("{{ route('deriveTSP') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json()) // Parse response as JSON
            .then(data => {
                // Close loading Swal
                Swal.close();
        
                // Handle response using SweetAlert2
                // Swal.fire({
                //     icon: 'success',
                //     title: 'Optimal Path',
                //     text: data.optimalPath
                // });
                Swal.fire({
                    icon: 'success',
                    title: 'Optimal path result',
                    html: '<p>' + data['optimalPath'] + '</p>' +
                        '<p>Total Weight: ' + data['totalWeight'] + '</p>'
                });
                console.log(data['locations']);
            })
            .catch(error => {
                // Close loading Swal
                Swal.close();

                // Handle error using SweetAlert2
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error:'+ error.message
                    // text: 'An error has occured, please try again later' 
                });
                console.error('Error:', error.message);  // Extract error message from error object
            });
        }
    </script>
</body>
</html>
