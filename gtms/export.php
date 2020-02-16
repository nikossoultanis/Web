<?php
    include 'functions.php';
    if ($_SESSION['user']['admin'] == 1) {
        $data = array();
        $query = "SELECT * FROM locations";
        $results = mysqli_query($conn, $query);
        while($temp = $results->fetch_assoc()){
            $data[] = $temp;
        }
        $data = json_encode($data);
        $fp = fopen('exports/exported.json', 'w');
        fwrite($fp, $data);
        fclose($fp);
        echo "Check Exports Folder";
    }
    header('location: admin.php');
    exit;

?>
