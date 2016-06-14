function initMap() {
    var coords = {'latitude':53.548805,'longitude':9.995161};
    var pos = {'coords':coords};
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

});