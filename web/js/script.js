function initMap() {
    var latitude = $('#latitude').val();
    var longitude = $('#longitude').val();
    latitude = parseFloat(latitude);
    longitude = parseFloat(longitude);
    var coords = {'lat':latitude,'lng':longitude};
    var mapDiv = document.getElementById('map');
    var map = new google.maps.Map(mapDiv, {
        center: coords,
        zoom: 14
    });
    map.data.loadGeoJson('app_dev.php/api/places?lat='+coords.lat+'&lng='+coords.lng);
}

function error(){
    var pos = {'latitude' : 51, 'longitude' : 10};
    initMap(pos);
}