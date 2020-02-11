<?php
    include 'functions.php';
    $query = "TRUNCATE `locations`";
    mysqli_query($conn, $query);
    header('location: admin.php');
    exit;
?>