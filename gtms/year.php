<?php
    include 'functions.php';
    $year = "2019";
    $result = array();
    $sql = "SELECT timestamp/1000 AS `timestamp` FROM locations;";
    $data = $conn->query($sql);
    while($temp = $data->fetch_assoc())
    {
        if(date('Y', $temp["timestamp"]) == $year){
            echo date('Y',$temp["timestamp"]) . "\n";
            array_push($result, date('Y',$temp["timestamp"]));
        }
    }
    echo json_encode($result);
?>