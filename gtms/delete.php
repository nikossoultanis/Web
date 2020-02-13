<?php
      if ($_SESSION['user']['admin'] != 1){
        header('location: user.php');
        exit;
    }
    include 'functions.php';
    $query = "TRUNCATE `locations`";
    mysqli_query($conn, $query);
    header('location: admin.php');
    exit;
?>