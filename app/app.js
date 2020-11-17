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

// Load the logs over AJAX
var lastId = 0;
function loadLogs(fromId) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if ( this.responseText.length > 2 ) {
        var logs = JSON.parse(this.responseText);
        for ( var log of logs ) {
          lastId = parseInt(log.id);
          var date = new Date(log.timestamp * 1000);
          var row = document.getElementById("log").insertRow(0);
	  row.insertCell(0).innerHTML = log.id;
          row.insertCell(1).innerHTML = log.channel;
          row.insertCell(2).innerHTML = date.toISOString();
          row.insertCell(3).innerHTML = log.source;
          row.insertCell(4).innerHTML = log.heard;
          row.insertCell(5).innerHTML = log.audio_level;
          row.insertCell(6).innerHTML = log.error;
          row.insertCell(7).innerHTML = log.dti;
          row.insertCell(8).innerHTML = log.object_name;
          row.insertCell(9).innerHTML = log.symbol;
          row.insertCell(10).innerHTML = log.latitude;
          row.insertCell(11).innerHTML = log.longitude;
          row.insertCell(12).innerHTML = log.speed;
          row.insertCell(13).innerHTML = log.course;
          row.insertCell(14).innerHTML = log.altitude;
          row.insertCell(15).innerHTML = log.frequency;
          row.insertCell(16).innerHTML = log.offset;
          row.insertCell(17).innerHTML = log.tone;
          row.insertCell(18).innerHTML = log.system;
          row.insertCell(19).innerHTML = log.status;
          row.insertCell(20).innerHTML = log.telemetry;
          row.insertCell(21).innerHTML = log.comment;
        }
      }
    }
  };
  xhttp.open("GET", "/log/" + fromId, true);
  xhttp.send();
}
setInterval(function(){ loadLogs(1+lastId); }, 3000);
