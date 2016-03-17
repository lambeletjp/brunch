function initMap(pos) {
    var crd = pos.coords;
    var mapDiv = document.getElementById('map');
    var map = new google.maps.Map(mapDiv, {
        center: {lat: crd.latitude, lng: crd.longitude},
        zoom: 14
    });
    map.data.loadGeoJson('app_dev.php/api/places?lat='+crd.latitude+'&lng='+crd.longitude);
}

function error(){
    var pos = {'latitude' : 51, 'longitude' : 10};
    initMap(pos);
}

$( document ).ready(function() {

    var optionsGeoLocation = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    };
    navigator.geolocation.getCurrentPosition(initMap, error, optionsGeoLocation);
});