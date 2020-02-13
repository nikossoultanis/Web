<?php    
    include 'functions.php';
    
    if (!isset($_SESSION['user'])) {
        header('location: sign_in.php');
        exit;
    }

    if (!empty($_POST['selected_day1']))
    {
        $sday1 = $_POST['selected_day1'];
    }
    else {$sday1 = 0;}
    if (!empty($_POST['selected_month1']))
    {
        $smonth1 = $_POST['selected_month1'];
    }else {$smonth1 = 0;}

    if (!empty($_POST['selected_year1']))
    {
        $syear1 = $_POST['selected_year1'];
    } else {$syear1 = 2009;}

    //-----------//
    if (!empty($_POST['selected_day2']))
    {
        $sday2 = $_POST['selected_day2'];
        if ($sday2 == 0) {
          $sday2 = 32;
        }
    }
    else {$sday2 = 32;}
    if (!empty($_POST['selected_month2']))
    {
        $smonth2 = $_POST['selected_month2'];
        if ($smonth2 == 0) {
          $smonth2 = 13;
        }
    }else {$smonth2 = 13;}

    if (!empty($_POST['selected_year2']))
    {
        $syear2 = $_POST['selected_year2'];
        if ($syear2 == 2009) {
          $syear2 = 2031;
        }
    } else {$syear2 = 2031;}

    $location = array();
    $final_loc = array();
    $userid = $_SESSION['user']['userid'];
    $query = "SELECT longitude, latitude, timestamp/1000 as `time`,  COUNT(*) AS count FROM locations WHERE userid = '$userid'  GROUP BY latitude, longitude";
    $results = mysqli_query($conn, $query);
    $E7 = 10**7;

    while($temp = $results->fetch_assoc())
    {
        if ( ((int)date('d', $temp["time"]) >= $sday1   && (int)date('d', $temp["time"]) <= $sday2   ) && 
             ((int)date('m', $temp["time"]) >= $smonth1 && (int)date('m', $temp["time"]) <= $smonth2 ) && 
             ((int)date('Y', $temp["time"]) >= $syear1  && (int)date('Y', $temp["time"]) <= $syear2  ) ) {
            $location['lng'] = ((double) $temp['longitude'] );
            $location['lat'] = ((double) $temp['latitude']);
            $location['count'] = (int) $temp['count'];
            array_push($final_loc, $location);
        }else{
            continue;
        }
    } 
    $final_loc = json_encode($final_loc);
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
 <form method="post" name="date-form" action="map.php">

    <div class="container" id="select">  
        <select name="selected_day1" class="bear-dates">
          <?php 
            for ($i=0; $i <= 31; $i++)
            {
              echo "<option value=\"$i\">";
              if ($i == 0) {
                echo "(From: Day)";
              }
              else {
                echo "$i";
              }
              echo "</option>";
            }
          ?>
        </select>
        <select name="selected_day2" class="bear-dates">
          <?php 
            for ($i=0; $i <= 31; $i++)
            {
              echo "<option value=\"$i\">";
              if ($i == 0) {
                echo "(To: Day)";
              }
              else {
                echo "$i";
              }
              echo "</option>";
            }
          ?>
        </select>

        <select name="selected_month1" class="bear-dates">
          <?php 
            for ($i=0; $i <= 12; $i++)
            {
              echo "<option value=\"$i\">";
              if ($i == 0) {
                echo "(From: Month)";
              }
              else {
                echo "$i";
              }
              echo "</option>";
            }
          ?>
        </select>
        <select name="selected_month2" class="bear-dates">
          <?php 
            for ($i=0; $i <= 12; $i++)
            {
              echo "<option value=\"$i\">";
              if ($i == 0) {
                echo "(To: Month)";
              }
              else {
                echo "$i";
              }
              echo "</option>";
            }
          ?>
        </select>

        <select name="selected_year1" class="bear-dates">
          <?php 
            for ($i=2015; $i <= 2023; $i++)
            {
              echo "<option value=\"$i\">";
              if ($i == 2015) {
                echo "(From: Year)";
              }
              else {
                echo "$i";
              }
              echo "</option>";
            }
          ?>
        </select>
        <select name="selected_year2" class="bear-dates">
          <?php 
            for ($i=2015; $i <= 2023; $i++)
            {
              echo "<option value=\"$i\">";
              if ($i == 2015) {
                echo "(To: Year)";
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

// // console.log(data22);

var testData = {
  max: 100,
  data: <?php echo $final_loc; ?>
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