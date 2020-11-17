// app.js

var map = L.map('map').setView([51.505, -0.09], 13);

L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/dark_all/{z}/{x}/{y}.png', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>. Map tiles by Carto, under CC BY 3.0. Data by OpenStreetMap, under ODbL',
    maxZoom: 18
}).addTo(map);

var realtime = L.realtime({
        url: '/stations',
        crossOrigin: true,
        type: 'json'
    }, {
        interval: 3 * 1000,
        removeMissing: true,
        onEachFeature: function(feature, layer) {
          layer.bindPopup(function (layer) {
            return layer.feature.properties.name;
          });
        }
    }).addTo(map);

realtime.on('update', function() {
    map.fitBounds(realtime.getBounds(), {maxZoom: 12});
});
