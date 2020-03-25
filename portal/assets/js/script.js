import $ from 'jquery';

function initMapPlace() {
    var mapDiv = document.getElementById('mapPlace');
    if(mapDiv) {
        var latitude = parseFloat(mapDiv.dataset.latitude);
        var longitude = parseFloat(mapDiv.dataset.longitude);
        var coords = {'lat': latitude, 'lng': longitude};
        var map = new google.maps.Map(mapDiv, {
            center: coords,
            zoom: 13
        });
        var marker = new google.maps.Marker({
            position: coords,
            map: map
        });
    }
}

function error(){
    var pos = {'latitude' : 51, 'longitude' : 10};
    initMap(pos);
    initMapPlace();
}

var $collectionHolder;
// setup an "add a Image" link
var $addImageLink = $('<a href="#" class="add_Image_link col-sm-10">Add a Image</a>');
var $newLinkLi = $('<div class="form-group"><div class="col-sm-2"></div></div>').append($addImageLink);

jQuery(document).ready(function() {
    $('.add-another-collection-widget').click(function (e) {
        var list = $($(this).attr('data-list-selector'));
        // Try to find the counter of the list or use the length of the list
        var counter = list.data('widget-counter') | list.children().length;

        // grab the prototype template
        var newWidget = list.attr('data-prototype');
        // replace the "__name__" used in the id and name of the prototype
        // with a number that's unique to your emails
        // end name attribute looks like name="contact[emails][2]"
        newWidget = newWidget.replace(/__name__/g, counter);
        // Increase the counter
        counter++;
        // And store it, the length cannot be used if deleting widgets is allowed
        list.data('widget-counter', counter);

        // create a new list element and add it to the list
        var newElem = $(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);
    });

    $('.place_slider').slick(
        {
            autoplay: true,
            centerMode: true,
            centerPadding: '60px',
            slidesToShow: 3,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 1
                    }
                }
            ]
        }
    );

});

$(document).on('ready', function() {
    $(".place_slider").slick({
        dots: true,
        infinite: true,
        centerMode: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        variableWidth: true,
        adaptiveHeight: true
    });

    initMapPlace();
    initMap();

});
