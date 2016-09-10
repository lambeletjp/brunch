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

console.log('ici');
var $collectionHolder;

jQuery(document).ready(function() {
    $collectionHolder = $('div.images');
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    addImageForm($collectionHolder, $collectionHolder);
});

function addImageForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    var $newFormLi = $('<div class="form-group"></div>').append(newForm);
    $newLinkLi.before($newFormLi);
}