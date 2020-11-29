/**
 * Show a location on a Leaflet map.
 */
(function() {
    "use strict";

    // initialize Leaflet
    var map = L.map('mapid').setView({lon: lon, lat: lat}, 10);

    // add the OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
    }).addTo(map);

    // show the scale bar on the lower left corner
    L.control.scale().addTo(map);

    // show a marker on the map
    L.marker({lon: lon, lat: lat}).bindPopup(locationName).addTo(map);
})();
