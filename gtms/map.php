<?php    
  include 'functions.php';
  
  if (!isset($_SESSION['user'])) {
    header('location: sign_in.php');
    exit;
  }

  $userid = $_SESSION['user']['userid'];
  $act_types = ["0", "EXITING_VEHICLE", "IN_BUS", "IN_CAR", "IN_FOUR_WHEELER_VEHICLE", "IN_RAIL_VEHICLE", "IN_ROAD_VEHICLE", "IN_TWO_WHEELER_VEHICLE", "IN_VEHICLE", "ON_BICYCLE", "ON_FOOT", "RUNNING", "STILL", "TILTING", "UNKNOWN", "WALKING"];
?>

<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">      
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
  integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
  crossorigin=""/>
  <link rel="stylesheet" href="style.css" />  
  <link rel='icon' href='favicon.png' type='image/x-icon' />
</head>
<div class="container ontop" id="select">
  <form method="post" name="date-form" id="filters" action="" onsubmit="refresh(); return false">
  <button type="button" class="home inline-button" onclick="window.location.href='user.php'">🏠</button>
      <select class="input-box" name="selected_day1" class="bear-dates">
        <?php 
          for ($i=0; $i <= 31; $i++) {
            echo "<option value=\"$i\">";
            if ($i == 0) { echo "(From: Day)"; }
            else { echo "$i"; }
            echo "</option>";
          } 
        ?>
      </select>
      <select class="input-box" name="selected_day2" class="bear-dates">
        <?php 
          for ($i=0; $i <= 31; $i++) { 
            echo "<option value=\"$i\">";
            if ($i == 0) { echo "(To: Day)"; }
            else { echo "$i"; }
            echo "</option>";
          }
        ?>
      </select>
      <select class="input-box" name="selected_week1" class="bear-dates">
        <?php 
          $weekday = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
          for ($i=0; $i <= 7; $i++) {
            echo "<option value=\"$i\">";
            if ($i == 0) { echo "(From: Weekday)"; }
            else { echo $weekday[$i - 1]; }
            echo "</option>";
          }
        ?>
      </select>
      <select class="input-box" name="selected_week2" class="bear-dates">
        <?php 
          for ($i=0; $i <= 7; $i++) {
            echo "<option value=\"$i\">";
            if ($i == 0) { echo "(To: Weekday)"; }
            else { echo $weekday[$i - 1]; }
            echo "</option>";
          }
        ?>
      </select>
      <select class="input-box" name="selected_month1" class="bear-dates">
        <?php 
          for ($i=0; $i <= 12; $i++) {
            echo "<option value=\"$i\">";
            if ($i == 0) { echo "(From: Month)"; }
            else { echo "$i"; }
            echo "</option>";
          }
        ?>
      </select>
      <select class="input-box" name="selected_month2" class="bear-dates">
        <?php 
          for ($i=0; $i <= 12; $i++) {
            echo "<option value=\"$i\">";
            if ($i == 0) { echo "(To: Month)"; }
            else { echo "$i"; }
            echo "</option>";
          }
        ?>
      </select>
      <select class="input-box" name="selected_year1" class="bear-dates">
        <?php 
          for ($i=2015; $i <= 2023; $i++) {
            echo "<option value=\"$i\">";
            if ($i == 2015) { echo "(From: Year)"; }
            else { echo "$i"; }
            echo "</option>";
          }
        ?>
      </select>
      <select class="input-box" name="selected_year2" class="bear-dates">
        <?php 
          for ($i=2015; $i <= 2023; $i++) {
            echo "<option value=\"$i\">";
            if ($i == 2015) { echo "(To: Year)"; }
            else { echo "$i"; }
            echo "</option>";
          }
        ?>
      </select>
      <div class="input-div" id="activity-btn" onclick="showCheckboxes()">
        <select class="input-box" ><option selected disabled hidden>Activities</option></select>
        <div class="overSelect"></div>
      <div id="checkboxes" class="input-div">
        <?php 
          for ($i = 0; $i < sizeof($act_types); $i++) {
            echo "<label class=\"input-box\" for=\"" . $act_types[$i] . "\"> <input type=\"checkbox\" name=\"act_types_chk[]\" value = \" " . $act_types[$i] . "\" />" . $act_types[$i] . "</label>";
          }
        ?>
      </div>
      </div>
      <div id="text"> <button class="inline-button">Select Date</button> </div>
      <script>
        var expanded = false;
        function showCheckboxes() {
          var checkboxes = document.getElementById("checkboxes");
          var act = document.getElementById("activity-btn");
          var pos = act.getBoundingClientRect();
          if (!expanded) {
            checkboxes.style.display = "block";
            checkboxes.style.left = pos.left;
            expanded = true;
          } else {
            checkboxes.style.display = "none";
            expanded = false;
          }
        }

      </script>
  </form>
</div>

<script>
  document.getElementById("filters").reset();
</script>


  <div id="map-canvas"></div>
  <script type="text/javascript" src="node_modules\heatmap.js\build\heatmap.js"></script>
  <script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
  <script type="text/javascript" src="leaflet-heatmap.js"></script>

  <script type="text/javascript">
    var baseLayer = L.tileLayer(
          'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
            attribution: '...',
            maxZoom: 18
          }
    );
    var cfg = {
      "radius": 25,
      "maxOpacity": .5,
      "scaleRadius": false,
      "useLocalExtrema": true,
      latField: 'lat',
      lngField: 'lng',
      valueField: 'count'
    };
    var heatmapLayer = new HeatmapOverlay(cfg);
    var map = new L.Map('map-canvas', {
      zoomControl: false,
      center: new L.LatLng(38.25, 21.75),
      zoom: 13,
      layers: [baseLayer, heatmapLayer]
    });

    function refresh(){
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var response = this.responseText;
          var testData = {
            max: 100,
            data: JSON.parse(response)
          };
          heatmapLayer.setData(testData);
        }
      };
      xmlhttp.open("POST", "map-backend.php", true);
      var filter = new FormData(document.getElementById("filters"));
      xmlhttp.send(filter);
    };

    refresh();
  </script>

</body>

</html>