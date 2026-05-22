<div class="bg-white p-4 rounded shadow mt-6">
    <h2 class="text-lg font-semibold mb-4">🚚 Live Driver Tracking</h2>

    <div id="map" style="width:100%; height:400px;"></div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZS3gsCGlGQfezo4o3ooNJcR7N1QmPhjU"></script>

<script>
let map;
let marker;

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: { lat: -27.4698, lng: 153.0251 }
    });

    marker = new google.maps.Marker({
        position: { lat: -27.4698, lng: 153.0251 },
        map: map
    });

    fetchLocation();
    setInterval(fetchLocation, 10000);
}

async function fetchLocation() {
    try {
        const response = await fetch('/api/driver/3/latest-location');
        const data = await response.json();

        if (data) {
            const position = {
                lat: parseFloat(data.lat),
                lng: parseFloat(data.lng)
            };

            marker.setPosition(position);
            map.setCenter(position);
        }
    } catch (error) {
        console.error('Error fetching location:', error);
    }
}

document.addEventListener('DOMContentLoaded', initMap);
</script>