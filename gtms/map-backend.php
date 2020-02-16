<?php    
  include 'functions.php';
  
  if (!isset($_SESSION['user'])) {
    header('location: sign_in.php');
    exit;
  }

  $userid = $_SESSION['user']['userid'];
  $act_types = ["0", "EXITING_VEHICLE", "IN_BUS", "IN_CAR", "IN_FOUR_WHEELER_VEHICLE", "IN_RAIL_VEHICLE", "IN_ROAD_VEHICLE", "IN_TWO_WHEELER_VEHICLE", "IN_VEHICLE", "ON_BICYCLE", "ON_FOOT", "RUNNING", "STILL", "TILTING", "UNKNOWN", "WALKING"];

  if (!empty($_POST['selected_week1']))  { $sweek1  = $_POST['selected_week1'];  } else { $sweek1  = 0; }
  if (!empty($_POST['selected_day1']))   { $sday1   = $_POST['selected_day1'];   } else { $sday1   = 0; }
  if (!empty($_POST['selected_month1'])) { $smonth1 = $_POST['selected_month1']; } else { $smonth1 = 0; }
  if (!empty($_POST['selected_year1']))  { $syear1  = $_POST['selected_year1'];  } else { $syear1  = 2015; }
  //----------//
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
  //-------------//
  $act_sql_string = "";
  if (!empty($_POST["act_types_chk"])){
    for ($i = 0; $i < sizeof($_POST["act_types_chk"]); $i++) {
      $temp = $_POST["act_types_chk"][$i];
      if ($i == 0) { $act_sql_string .= " AND "; }
      else { $act_sql_string .= " OR "; }
      $act_sql_string .= "activity_type = \"" . trim($temp) . "\"";
    }
  }

  $location = array();
  $final_loc = array();
  if ($_SESSION['user']['admin'] == 1){
    $query = "SELECT longitude, latitude, timestamp/1000 as `timestamp`,  COUNT(*) AS count FROM locations WHERE 1" . $act_sql_string . " GROUP BY latitude, longitude";
  }
  else {
    $query = "SELECT longitude, latitude, timestamp/1000 as `timestamp`,  COUNT(*) AS count FROM locations WHERE userid = '$userid' " . $act_sql_string . " GROUP BY latitude, longitude";
  }

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

  echo $final_loc;
?>