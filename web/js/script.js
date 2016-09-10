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

var $collectionHolder;

// setup an "add a Image" link
var $addImageLink = $('<a href="#" class="add_Image_link col-sm-10">Add a Image</a>');
var $newLinkLi = $('<div class="form-group"><div class="col-sm-2"></div></div>').append($addImageLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of Images
    $collectionHolder = $('div.images');

    $collectionHolder.find('images_list').each(function() {
        addTagFormDeleteLink($(this));
    });

    // add the "add a Image" anchor and li to the Images ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addImageLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new Image form (see next code block)
        addImageForm($collectionHolder, $newLinkLi);
    });
});

function addImageForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<div class="images_list"></div>').append(newForm);
    $newLinkLi.before($newFormLi);
}
