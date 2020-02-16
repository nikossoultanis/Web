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
  //-----------//
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
  //-----------//
  $act_filter = array();
  if (!empty($_POST["act_types_chk"])){
    for ($i = 0; $i < sizeof($_POST["act_types_chk"]); $i++) {
      $act_filter[$i] = trim($_POST["act_types_chk"][$i]);
    }
  } else { $act_filter = $act_types; }

  $sql = "SELECT timestamp/1000 as `timestamp`, activity_type FROM locations WHERE userid = '$userid'";
  $results = $conn->query($sql);

  $year_count = array_fill(0, 8, 0);
  $month_count = array_fill(0, 12, 0);
  $week_count = array_fill(0, 7, 0);
  $day_count = array_fill(0, 31, 0);
  $activity_count = array_fill(0, sizeof($act_types), 0);

  while($temp = $results->fetch_assoc()) {
    if ( ((int)date('N', $temp["timestamp"]) >= $sweek1  && (int)date('N', $temp["timestamp"]) <= $sweek2  ) &&
         ((int)date('d', $temp["timestamp"]) >= $sday1   && (int)date('d', $temp["timestamp"]) <= $sday2   ) && 
         ((int)date('m', $temp["timestamp"]) >= $smonth1 && (int)date('m', $temp["timestamp"]) <= $smonth2 ) && 
         ((int)date('Y', $temp["timestamp"]) >= $syear1  && (int)date('Y', $temp["timestamp"]) <= $syear2  ) &&
         ( in_array($temp["activity_type"], $act_filter) ) ) {
      for ($i = 2016; $i <= 2023; $i++) {
        if( (int)date('Y', $temp["timestamp"] ) == $i) { $year_count[$i - 2016]++; } 
      }
      for ($i = 1; $i <= 12; $i++) {
        if( (int)date('m', $temp["timestamp"] ) == $i) { $month_count[$i - 1]++; }
      }
      for ($i = 1; $i <= 31; $i++) {
        if( (int)date('d', $temp["timestamp"] ) == $i) { $day_count[$i - 1]++; } 
      }
      for ($i = 1; $i <= 7; $i++) {
        if( (int)date('N', $temp["timestamp"] ) == $i) { $week_count[$i - 1]++; } 
      }
      for ($i = 0; $i < sizeof($act_types); $i++) {
        if( !strcmp($temp["activity_type"], $act_types[$i])) {
          $activity_count[$i]++; }
      }
    } else { continue; }
  } 

  $year_count  = json_encode($year_count);
  $month_count = json_encode($month_count);
  $day_count   = json_encode($day_count);
  $week_count  = json_encode($week_count);
  $activity_count = json_encode($activity_count);

  echo "$activity_count|$year_count|$month_count|$day_count|$week_count";
?>
