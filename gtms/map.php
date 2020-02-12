<?php    
    include 'functions.php';
    if (!isset($_SESSION['user'])) {
        header('location: sign_in.php');
        exit;
    }
    $location = array();
    $final_loc = array();
    $userid = $_SESSION['user']['userid'];
    $query = "SELECT longitude, latitude, COUNT(*) AS count FROM locations WHERE userid = 'e30bd96d7ef1ce03629ffe3583d1211e' GROUP BY latitude, longitude";
    $results = mysqli_query($conn, $query);
    $E7 = 10**7;
    while($temp = $results->fetch_assoc())
    {

        $location['lng'] = ((double) $temp['longitude'] );
        $location['lat'] = ((double) $temp['latitude']);
        $location['count'] = (int) $temp['count'];
        array_push($final_loc, $location);
    }
    $final = json_encode($final_loc);
    var_dump($final);
?>
<html>
<head>
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
   integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
   crossorigin=""/>
   <link rel="stylesheet" href="style.css" />  

</head>

<body>
<div id="map-canvas"></div>
<script type="text/javascript" src="node_modules\heatmap.js\build\heatmap.js"></script>
<script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
<script type="text/javascript" src="leaflet-heatmap.js"></script>

<script type="text/javascript">
var data22 = <?php echo $final ?>;
console.log(data22);
// var data222 = data22.substring(1, data22 .length-1);
// console.log(data222);
var testData = {
  max: 100,
  data: data22
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
  center: new L.LatLng(25.6586, -80.3568),
  zoom: 4,
  layers: [baseLayer, heatmapLayer]
});

heatmapLayer.setData(testData);

</script>

</body>

</html>