<?php
    include 'functions.php';

    $result = array();
    $sql = "SELECT userid, COUNT(*) AS `entries` FROM locations GROUP BY userid";
    $data = $conn->query($sql);
    while($temp = $data->fetch_assoc())
    {
        array_push($result, $temp);
    }
    echo json_encode($result);
?>
