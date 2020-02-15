<?php    
  include 'functions.php';
  
  if (!isset($_SESSION['user'])) {
    header('location: sign_in.php');
    exit;
  }

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

  $userid = $_SESSION['user']['userid'];
?>


<!-- DAY MONTH YEAR FETCH -->

<?php 
  $sql = "SELECT timestamp/1000 as `timestamp` FROM locations WHERE userid = '$userid';";
  $results = $conn->query($sql);

  $year_count = array_fill(0, 8, 0);
  $month_count = array_fill(0, 12, 0);
  $week_count = array_fill(0, 7, 0);
  $day_count = array_fill(0, 31, 0);


  while($temp = $results->fetch_assoc()) {
    if ( ((int)date('N', $temp["timestamp"]) >= $sweek1  && (int)date('N', $temp["timestamp"]) <= $sweek2  ) &&
         ((int)date('d', $temp["timestamp"]) >= $sday1   && (int)date('d', $temp["timestamp"]) <= $sday2   ) && 
         ((int)date('m', $temp["timestamp"]) >= $smonth1 && (int)date('m', $temp["timestamp"]) <= $smonth2 ) && 
         ((int)date('Y', $temp["timestamp"]) >= $syear1  && (int)date('Y', $temp["timestamp"]) <= $syear2  ) ) {
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
    } else { continue; }
  } 

  $year_count  = json_encode($year_count);
  $month_count = json_encode($month_count);
  $day_count   = json_encode($day_count);
  $week_count  = json_encode($week_count);

// ACTIVITIES
  $activity_type = array();
  $activity_count = array();
  $sql = "SELECT activity_type, COUNT(*) AS `number` FROM locations WHERE userid = '$userid' GROUP BY activity_type" ;
  $data_act = $conn->query($sql);
  while($temp = $data_act->fetch_assoc()) {
    array_push($activity_type, $temp["activity_type"]);
    array_push($activity_count, $temp["number"]);
  }
  $activity_type = json_encode($activity_type);
  $activity_count = json_encode($activity_count);
?>

<!-- ADMIN: ENTRIES PER USER FETCH-->

<!-- HTML -->
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

<form method="post" name="date-form" action="dashboard.php">
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
      <div id="text"> <button class="inline-button">Select Date</button> </div>
    </div>
  </form>


<canvas id="activity_chart" width="200" height="50"></canvas>
<canvas id="week_chart" width="200" height="50"></canvas>
<canvas id="day_chart" width="200" height="50"></canvas>
<canvas id="month_chart" width="200" height="50"></canvas>
<canvas id="year_chart" width="200" height="50"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<script>
//graph ACTIVITIES
var activity_chart_ctx = document.getElementById('activity_chart').getContext('2d');
var activity_chart = new Chart(activity_chart_ctx, {
    type: 'bar',
    data: {
        labels: [],
        
        datasets: [{
            data: [],
            borderWidth: 1,
            borderColor: '#0c7acb',
            backgroundColor: '#1c8adb',
        }]
    },
    options: {
        legend: {
            display: false
        },
        responsive: true,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    fontColor: "white"
                }
            }], 
            xAxes: [{
                ticks: {
                    fontColor: "white"
                }
            }]
        }
    }
});

<?php echo "var activity_type = " . $activity_type . ";\n"; ?>
<?php echo "var activity_count = " . $activity_count . ";\n"; ?>
for (var i = 0; i < activity_count.length; i++) {
  activity_chart.data.labels.push(activity_type[i]);
  activity_chart.data.datasets[0].data.push(activity_count[i]);
  activity_chart.update();
}
activity_chart.update();


// graph WEEK
var week_chart_ctx = document.getElementById('week_chart').getContext('2d');
  var week_chart = new Chart(week_chart_ctx, {
      type: 'bar',
      data: {
          labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
          datasets: [{
            data: [],
            borderWidth: 1,
            borderColor: '#0c7acb',
            backgroundColor: '#1c8adb',
        }]
    },
    options: {
        legend: {
            display: false
        },
        responsive: true,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    fontColor: "white"
                }
            }], 
            xAxes: [{
                ticks: {
                    fontColor: "white"
                }
            }]
        }
    }
});

<?php echo "var week_count = " . $week_count . ";\n"; ?>
  for (var i = 0; i < week_count.length; i++) {
    week_chart.data.datasets[0].data.push(week_count[i]);
    week_chart.update();
  }
  week_chart.update();

// graph DAY
var day_chart_ctx = document.getElementById('day_chart').getContext('2d');
  var day_chart = new Chart(day_chart_ctx, {
      type: 'bar',
      data: {
          labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31"],
          datasets: [{
            data: [],
            borderWidth: 1,
            borderColor: '#0c7acb',
            backgroundColor: '#1c8adb',
        }]
    },
    options: {
        legend: {
            display: false
        },
        responsive: true,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    fontColor: "white"
                }
            }], 
            xAxes: [{
                ticks: {
                    fontColor: "white"
                }
            }]
        }
    }
});

<?php echo "var day_count = " . $day_count . ";\n"; ?>
  for (var i = 0; i < day_count.length; i++) {
    day_chart.data.datasets[0].data.push(day_count[i]);
    day_chart.update();
  }
  day_chart.update();




// graph MONTH
var month_chart_ctx = document.getElementById('month_chart').getContext('2d');
var month_chart = new Chart(month_chart_ctx, {
    type: 'bar',
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            data: [],
            borderWidth: 1,
            borderColor: '#0c7acb',
            backgroundColor: '#1c8adb',
        }]
    },
    options: {
        legend: {
            display: false
        },
        responsive: true,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    fontColor: "white"
                }
            }], 
            xAxes: [{
                ticks: {
                    fontColor: "white"
                }
            }]
        }
    }
});

<?php echo "var month_count = " . $month_count . ";\n"; ?>
    for (var i = 0; i < month_count.length; i++) {
      month_chart.data.datasets[0].data.push(month_count[i]);
      month_chart.update();
    }
    month_chart.update();

// graph YEAR
var year_chart_ctx = document.getElementById('year_chart').getContext('2d');
  var year_chart = new Chart(year_chart_ctx, {
      type: 'bar',
      data: {
          labels: ["2016", "2017", "2018", "2019", "2020", "2021", "2022", "2023"],
          datasets: [{
            data: [],
            borderWidth: 1,
            borderColor: '#0c7acb',
            backgroundColor: '#1c8adb',
        }]
    },
    options: {
        legend: {
            display: false
        },
        responsive: true,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    fontColor: "white"
                }
            }], 
            xAxes: [{
                ticks: {
                    fontColor: "white"
                }
            }]
        }
    }
});

<?php echo "var year_count = " . $year_count . ";\n"; ?>
    for (var i = 0; i < year_count.length; i++) {
      year_chart.data.datasets[0].data.push(year_count[i]);
      year_chart.update();
    }
    year_chart.update();
</script>

<?php 
