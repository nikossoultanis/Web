<?php    
    include 'functions.php';
    if (!isset($_SESSION['user'])) {
        header('location: sign_in.php');
        exit;
    }

    if (!empty($_POST['selected_day']))
    {
        $sday = $_POST['selected_day'];
    }
    else {$sday = 0;}
    if (!empty($_POST['selected_month']))
    {
        $smonth = $_POST['selected_month'];
    }else {$smonth = 0;}

    if (!empty($_POST['selected_year']))
    {
        $syear = $_POST['selected_year'];
    } else {$syear = 2009;}

    $location = array();
    $final_loc = array();
    $userid = $_SESSION['user']['userid'];
    $query = "SELECT longitude, latitude, timestamp/1000 as `time`,  COUNT(*) AS count FROM locations WHERE userid = '$userid'  GROUP BY latitude, longitude";
    $results = mysqli_query($conn, $query);
    var_dump($results);
    $E7 = 10**7;
    echo "gamw thn panagia $sday"."<br>";

    echo "exw ena syear = $syear";

    while($temp = $results->fetch_assoc())
    {
        if($sday == 22){
            echo "<br>eimai re mlk <br>";
            echo (int)date('d', $temp["time"]);
        }
        if (((int)date('d', $temp["time"]) == $sday || $sday == 0) && ((int)date('m', $temp["time"]) == $smonth || $smonth == 0) && ((int)date('Y', $temp["time"]) == $syear || $syear == 2009)) {
            echo "douleuw";
        }
        if (((int)date('d', $temp["time"]) == $sday || $sday == 0) && ((int)date('m', $temp["time"]) == $smonth || $smonth == 0) && ((int)date('Y', $temp["time"]) == $syear || $syear == 2009)) {
            $location['lng'] = ((double) $temp['longitude'] );
            $location['lat'] = ((double) $temp['latitude']);
            $location['count'] = (int) $temp['count'];
            echo "<br>mesa";
            //echo date('d:m:Y', $temp['time']);
            array_push($final_loc, $location);
        }else{
            continue;
        }
    } 
    //echo json_encode($final_loc);
?>