<?php 
  include 'map_filter.php'
?>

<html>
<head>
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
   integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
   crossorigin=""/>
   <link rel="stylesheet" href="style.css" />  
   <link rel='icon' href='favicon.png' type='image/x-icon' />

</head>

<body>
 <form method="post" name="date-form" action="map_filter.php">
    <div class="container" id="select">

        <select name="selected_day" class="bear-dates">
          <?php 
            for ($i=0; $i <= 31; $i++)
            {
              echo "<option value=\"$i\">";
              if ($i == 0) {
                echo "(None)";
              }
              else {
                echo "$i";
              }
              echo "</option>";
            }
          ?>
        </select>

        <select name="selected_month" class="bear-dates">
          <?php 
            for ($i=0; $i <= 12; $i++)
            {
              echo "<option value=\"$i\">";
              if ($i == 0) {
                echo "(None)";
              }
              else {
                echo "$i";
              }
              echo "</option>";
            }
          ?>
        </select>

        <select name="selected_year" class="bear-dates">
          <?php 
            for ($i=2009; $i <= 2030; $i++)
            {
              echo "<option value=\"$i\">";
              if ($i == 2009) {
                echo "(None)";
              }
              else {
                echo "$i";
              }
              echo "</option>";
            }
          ?>
        </select>
    <div id="text">
        <button class="inline-button">Select Date</button>
    </div>
    </div>

 </form>
<div id="map-canvas"></div>
<script type="text/javascript" src="node_modules\heatmap.js\build\heatmap.js"></script>
<script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
<script type="text/javascript" src="leaflet-heatmap.js"></script>

<script type="text/javascript">

// var data22 = 
// console.log(data22);

var testData = {
  max: 100,
  data: []
};
console.log(testData);
var baseLayer = L.tileLayer(
  'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
    attribution: '...',
    maxZoom: 18
  }
);

var cfg = {
  // radius should be small ONLY if scaleRadius is true (or small radius is intended)
  // if scaleRadius is false it will be the constant radius used in pixels
  "radius": 25,
  "maxOpacity": .5,
  // scales the radius based on map zoom
  "scaleRadius": false,
  // if set to false the heatmap uses the global maximum for colorization
  // if activated: uses the data maximum within the current map boundaries
  //   (there will always be a red spot with useLocalExtremas true)
  "useLocalExtrema": true,
  // which field name in your data represents the latitude - default "lat"
  latField: 'lat',
  // which field name in your data represents the longitude - default "lng"
  lngField: 'lng',
  // which field name in your data represents the data value - default "value"
  valueField: 'count'
};


var heatmapLayer = new HeatmapOverlay(cfg);

var map = new L.Map('map-canvas', {
  center: new L.LatLng(38.25, 21.75),
  zoom: 13,
  layers: [baseLayer, heatmapLayer]
});

heatmapLayer.setData(testData);

</script>





</body>

</html>