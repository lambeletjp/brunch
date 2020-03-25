import $ from "jquery";

function initMap() {
    var latitude = $('#latitude').val();
    var longitude = $('#longitude').val();
    latitude = parseFloat(latitude);
    longitude = parseFloat(longitude);
    var coords = {'lat':latitude,'lng':longitude};
    var mapDiv = document.getElementById('map');
    if(mapDiv) {
        var map = new google.maps.Map(mapDiv, {
            center: coords,
            zoom: 13
        });

        var url = '/api/places?lat=' + coords.lat + '&lng=' + coords.lng;
        $.getJSON(url, function (data) {
            var items = [];
            var infoWindowOpen = null;
            $.each(data, function (key, place) {
                var coords = {'lat': place.latitude, 'lng': place.longitude};
                var marker = new google.maps.Marker({
                    position: coords,
                    map: map,
                    title: 'Click to zoom'
                });

                marker.addListener('click', function () {
                    var contentString = place.google_info_box;

                    var infowindow = new google.maps.InfoWindow({
                        content: contentString
                    });

                    if (infoWindowOpen) {
                        infoWindowOpen.close();
                    }
                    infowindow.open(map, marker);
                    infoWindowOpen = infowindow;
                });
            });
        });
    }
}
window.initMap = initMap;
