<?php
    include 'functions.php';
    $hour = "23";
    $result = array();
    $sql = "SELECT timestamp/1000 AS `timestamp` FROM locations;";
    $data = $conn->query($sql);
    while($temp = $data->fetch_assoc())
    {
        if(date('H', $temp["timestamp"]) == $hour){
            echo date('H',$temp["timestamp"]) . "\n";
            array_push($result, date('H',$temp["timestamp"]));
        }
    }
    echo json_encode($result);
?>
