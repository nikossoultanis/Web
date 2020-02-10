<?php
    include 'functions.php';
    $day = "Fri";
    $result = array();
    $sql = "SELECT timestamp/1000 AS `timestamp` FROM locations;";
    $data = $conn->query($sql);
    while($temp = $data->fetch_assoc())
    {
        if(date('D', $temp["timestamp"]) == $day){
            echo date('D',$temp["timestamp"]) . "\n";
            array_push($result, date('D',$temp["timestamp"]));
        }
    }
    echo json_encode($result);
?>
