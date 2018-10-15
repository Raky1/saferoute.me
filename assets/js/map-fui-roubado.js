
$(document).ready(function() {

    var map = L.map('map').setView([-22.0154, -47.8911], 13); //São Carlos
    map.zoomControl.setPosition('topright');

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    //add marker
    var marker = null;
    var searching_address = false; //state of searching

    function onMapClick(e) {
        if (searching_address == false) {
            //check marker
            if (marker == null) {
                marker = L.marker(e.latlng).addTo(map);
            } else {
                marker.setLatLng(e.latlng);
            }

            //display latlng
            document.getElementById("input_latitude").value = e.latlng.lat;
            document.getElementById("input_longitude").value = e.latlng.lng;

            //searching address
            searching_address = true;
            $.getJSON('https://nominatim.openstreetmap.org/reverse', {
                lat: e.latlng.lat,
                lon: e.latlng.lng,
                format: 'json'
            }, function (result) {
                document.getElementById("input_address").value = result.address.road + "-" + result.address.city + "-" + result.address.state + "-" + result.address.country;
                searching_address = false;
            });
        }
    }
    
    map.on('click', onMapClick);



    //display current day and time
    document.getElementById("input_day").value = new Date().toISOString().slice(0,10);

    document.getElementById("input_time").value = new Date().toLocaleTimeString('pt-BR', { hour12: false,
        hour: "numeric", 
        minute: "numeric"});


    //add searchboxcontrol
    L.control.searchbox().addTo(map);

});
