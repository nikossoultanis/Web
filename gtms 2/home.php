<?php
    if (isset($_SESSION['user']) && $_SESSION['user']['admin'] == 1) {
        echo "admin";
        header('location: admin.php');
        exit;
    }
    elseif(isset($_SESSION['user']) && $_SESSION['user']['admin'] == 0){
        header('location: user.php');
        exit;
        echo "admin";
    }else{
        header('location: sign_in.php');
        exit;
    }
?>
