import $ from "jquery";

$(document).ready(function () {
    if ($('.mapsEnabled').length) {
        let autocomplete;
        autocomplete = new google.maps.places.Autocomplete((document.getElementById('addressQuery')), {
            types: ['geocode']
        });

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            let place = autocomplete.getPlace();
                $('.address').val(place.address_components[0].long_name + " " + place.address_components[1].long_name);
                $('.city').val(place.address_components[2].long_name);
                $('.state').val(place.address_components[4].short_name);
                $('.zip').val(place.address_components[6].short_name);
        });
    } // if maps is enabled. otherwise this will just be blank
});

