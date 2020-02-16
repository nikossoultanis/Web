<?php    
  include 'functions.php';
  
  if (!isset($_SESSION['user'])) {
    header('location: sign_in.php');
    exit;
  }

  $userid = $_SESSION['user']['userid'];
  $act_types = ["0", "EXITING_VEHICLE", "IN_BUS", "IN_CAR", "IN_FOUR_WHEELER_VEHICLE", "IN_RAIL_VEHICLE", "IN_ROAD_VEHICLE", "IN_TWO_WHEELER_VEHICLE", "IN_VEHICLE", "ON_BICYCLE", "ON_FOOT", "RUNNING", "STILL", "TILTING", "UNKNOWN", "WALKING"]

?>

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

  <script>
    function refresh(){
      var req = new XMLHttpRequest();
      activity_chart.data.datasets[0].data = [];
      year_chart.data.datasets[0].data = [];
      month_chart.data.datasets[0].data = [];
      day_chart.data.datasets[0].data = [];
      week_chart.data.datasets[0].data = [];

      req.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          console.log(this.responseText);
          var response_part = this.responseText.split('|');
          var activity_count = JSON.parse(response_part[0]);
          var year_count = JSON.parse(response_part[1]);
          var month_count = JSON.parse(response_part[2]);
          var day_count = JSON.parse(response_part[3]);
          var week_count = JSON.parse(response_part[4]);
          for (var i = 0; i < activity_count.length; i++) {
            activity_chart.data.datasets[0].data.push(activity_count[i]);
            activity_chart.update();
          }
          for (var i = 0; i < year_count.length; i++){
            year_chart.data.datasets[0].data.push(year_count[i]);
            year_chart.update();
          }
          for (var i = 0; i < month_count.length; i++){
            month_chart.data.datasets[0].data.push(month_count[i]);
            month_chart.update();
          }
          for (var i = 0; i < day_count.length; i++){
            day_chart.data.datasets[0].data.push(day_count[i]);
            day_chart.update();
          }
          for (var i = 0; i < week_count.length; i++){
            week_chart.data.datasets[0].data.push(week_count[i]);
            week_chart.update();
          }
          activity_chart.update();
          year_chart.update();
          month_chart.update();
          day_chart.update();
          week_chart.update();
        }
      };
      req.open("POST", "dashboard-backend.php", true);
      var filter = new FormData(document.getElementById("filters"));
      req.send(filter);
    };
  </script>
  

<div class="charts-form">
  <h3 class="welcome">Activities</h3>
  <canvas id="activity_chart" width="200" height="50"></canvas>
  <h3 class="welcome">Weekdays</h3>
  <canvas id="week_chart" width="200" height="50"></canvas>
  <h3 class="welcome">Days</h3>
  <canvas id="day_chart" width="200" height="50"></canvas>
  <h3 class="welcome">Months</h3>
  <canvas id="month_chart" width="200" height="50"></canvas>
  <h3 class="welcome">Years</h3>
  <canvas id="year_chart" width="200" height="50"></canvas>
</div>





<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<script>
//graph ACTIVITIES
var activity_chart_ctx = document.getElementById('activity_chart').getContext('2d');
var activity_chart = new Chart(activity_chart_ctx, {
    type: 'bar',
    data: {
        labels: ["0", "EXITING_VEHICLE", "IN_BUS", "IN_CAR", "IN_FOUR_WHEELER_VEHICLE", "IN_RAIL_VEHICLE", "IN_ROAD_VEHICLE", "IN_TWO_WHEELER_VEHICLE", "IN_VEHICLE", "ON_BICYCLE", "ON_FOOT", "RUNNING", "STILL", "TILTING", "UNKNOWN", "WALKING"],
        
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
// DAY
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
//MONTH
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
//YEAR
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

refresh();
</script>


