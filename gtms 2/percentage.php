<?php
    include 'functions.php';

    $result = array();
    $sql = "SELECT activity_type, COUNT(*) AS `number` FROM locations GROUP BY activity_type";
    $data = $conn->query($sql);
    while($temp = $data->fetch_assoc())
    {
        array_push($result, $temp);
    }
    echo json_encode($result);
?>
