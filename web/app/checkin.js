// Initialize the map
const map = L.map('map').setView([0, 0], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

// Access user location and update map
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        position => {
            const { latitude, longitude } = position.coords;
            map.setView([latitude, longitude], 15);
            L.marker([latitude, longitude]).addTo(map).bindPopup("You are here").openPopup();
                                // Update the GPS coordinates input field
                                const gpsInput = document.getElementById('checkin-gps');
                                gpsInput.value = `${latitude},${longitude}`;
            
        },
        error => {
            console.error('Geolocation error:', error);
        }
    );
} else {
    alert("Geolocation is not supported by your browser.");
}

// Access camera
const camera = document.getElementById('camera');
const captureBtn = document.getElementById('capture-btn');
const checkinImgInput = document.getElementById('checkin-img');

// Start camera
navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
        camera.srcObject = stream;
    })
    .catch(error => {
        console.error("Error accessing the camera: ", error);
    });

// Capture image when button is clicked
captureBtn.addEventListener('click', () => {
    // Create a canvas to capture the image
    const canvas = document.createElement('canvas');
    canvas.width = camera.videoWidth;
    canvas.height = camera.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(camera, 0, 0, canvas.width, canvas.height);
    
    // Convert the image to Base64 format
    const imageData = canvas.toDataURL('image/png');
    
    // Set the image in the input field
    checkinImgInput.value = imageData;
});
