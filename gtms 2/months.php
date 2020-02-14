<?php
    include 'functions.php';

    $month_count = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

    $result = array();
    $sql = "SELECT timestamp/1000 AS `timestamp` FROM locations;";
    $data = $conn->query($sql);
    while($temp = $data->fetch_assoc())
    {
        if(date('m', $temp["timestamp"]) == "01"){
            $month_count[0]++;
        }
        if(date('m', $temp["timestamp"]) == "02"){
            $month_count[1]++;
        }
        if(date('m', $temp["timestamp"]) == "03"){
            $month_count[2]++;
        }
        if(date('m', $temp["timestamp"]) == "04"){
            $month_count[3]++;
        }
        if(date('m', $temp["timestamp"]) == "05"){
            $month_count[4]++;
        }
        if(date('m', $temp["timestamp"]) == "06"){
            $month_count[5]++;
        }
        if(date('m', $temp["timestamp"]) == "07"){
            $month_count[6]++;
        }
        if(date('m', $temp["timestamp"]) == "08"){
            $month_count[7]++;
        }
        if(date('m', $temp["timestamp"]) == "09"){
            $month_count[8]++;
        }
        if(date('m', $temp["timestamp"]) == "10"){
            $month_count[9]++;
        }
        if(date('m', $temp["timestamp"]) == "11"){
            $month_count[10]++;
        }
        if(date('m', $temp["timestamp"]) == "12"){
            $month_count[11]++;
        }

    }
    echo json_encode($month_count);
   // echo json_encode($result);
?>
