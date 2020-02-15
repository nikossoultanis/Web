<?php    
  include 'functions.php';
  
  if (!isset($_SESSION['user'])) {
    header('location: sign_in.php');
    exit;
  }

  $userid = $_SESSION['user']['userid'];

  if (!empty($_POST['selected_week1']))  { $sweek1  = $_POST['selected_week1'];  } else { $sweek1  = 0; }
  if (!empty($_POST['selected_day1']))   { $sday1   = $_POST['selected_day1'];   } else { $sday1   = 0; }
  if (!empty($_POST['selected_month1'])) { $smonth1 = $_POST['selected_month1']; } else { $smonth1 = 0; }
  if (!empty($_POST['selected_year1']))  { $syear1  = $_POST['selected_year1'];  } else { $syear1  = 2015; }

  if (!empty($_POST['selected_week2'])) {
    $sweek2 = $_POST['selected_week2'];
    if ($sweek2 == 0) { $sweek2 = 8; }
  } else { $sweek2 = 8; }
  if (!empty($_POST['selected_day2'])) {
    $sday2 = $_POST['selected_day2'];
    if ($sday2 == 0) { $sday2 = 32; }
  } else { $sday2 = 32; }
  if (!empty($_POST['selected_month2'])) {
    $smonth2 = $_POST['selected_month2'];
    if ($smonth2 == 0) { $smonth2 = 13; }
  } else { $smonth2 = 13; }
  if (!empty($_POST['selected_year2'])) {
    $syear2 = $_POST['selected_year2'];
    if ($syear2 == 2015) { $syear2 = 2024; }
  } else { $syear2 = 2024; }

  $act_types = array();
  $act_query = "SELECT DISTINCT activity_type FROM locations WHERE userid = '$userid'";
  $act_results = mysqli_query($conn, $act_query);
  while($temp = $act_results->fetch_assoc()) {
    array_push($act_types, $temp["activity_type"]);
  }
  $act_sql_string = "";
  if (isset($_POST["act_types_chk"])){
    for ($i = 0; $i < sizeof($_POST["act_types_chk"]); $i++) {
      $temp = $_POST["act_types_chk"][$i];
      if ($i == 0) { $act_sql_string .= " AND "; }
      else { $act_sql_string .= " OR "; }
      $act_sql_string .= "activity_type = \"" . trim($temp) . "\"";
    }
  }

  $location = array();
  $final_loc = array();
  $query = "SELECT longitude, latitude, timestamp/1000 as `timestamp`,  COUNT(*) AS count FROM locations WHERE userid = '$userid' " . $act_sql_string . " GROUP BY latitude, longitude";
  echo "$query";
  $results = mysqli_query($conn, $query);
  while($temp = $results->fetch_assoc()) {
    if ( ((int)date('N', $temp["timestamp"]) >= $sweek1  && (int)date('N', $temp["timestamp"]) <= $sweek2  ) &&
         ((int)date('d', $temp["timestamp"]) >= $sday1   && (int)date('d', $temp["timestamp"]) <= $sday2   ) && 
         ((int)date('m', $temp["timestamp"]) >= $smonth1 && (int)date('m', $temp["timestamp"]) <= $smonth2 ) && 
         ((int)date('Y', $temp["timestamp"]) >= $syear1  && (int)date('Y', $temp["timestamp"]) <= $syear2  ) ) {
        $location['lng'] = ((double) $temp['longitude'] );
        $location['lat'] = ((double) $temp['latitude']);
        $location['count'] = (int) $temp['count'];
        array_push($final_loc, $location);
    } else { continue; }
  } 
  $final_loc = json_encode($final_loc);


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

<body>
  <form method="post" name="date-form" action="map.php">
    <div class="container ontop" id="select">  
      <button class="home" formaction="user.php">🏠</button>
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
   </div>

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


  <div id="map-canvas"></div>
  <script type="text/javascript" src="node_modules\heatmap.js\build\heatmap.js"></script>
  <script src="http://cdn.leafletjs.com/leaflet/v0.7.7/leaflet.js"></script>
  <script type="text/javascript" src="leaflet-heatmap.js"></script>

  <script type="text/javascript">
    var testData = {
      max: 100,
      data: <?php echo $final_loc; ?>
    };
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
    heatmapLayer.setData(testData);
  </script>

</body>

</html>