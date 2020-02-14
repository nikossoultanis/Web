<?php
    include 'functions.php';

    if (!isset($_SESSION['user'])) {
        header('location: sign_in.php');
        exit;
    }
?>

<html>
    <head>  
        <meta name="viewport" content="width=device-width, initial-scale=1">  
        <link rel="stylesheet" href="style.css" />
        <link rel='icon' href='favicon.png' type='image/x-icon' />
        <?php
            $username_disp = $_SESSION['user']['username'];
            $userid = $_SESSION['user']['userid'];
            echo "<title>$username_disp: Upload</title>";
        ?>
    </head>
    <form action="parser.php" class="sign-in-form" method="POST" enctype="multipart/form-data">
        <?php
            $username_disp = $_SESSION['user']['username'];
            $userid = $_SESSION['user']['userid'];
            echo "<h1>😃 $username_disp<h1>";
        ?>
    <div class="container">
        <div class="button-wrap">
            <label class ="new-button" for="upload"> Choose File</label>
            <input id="upload" type="file" name="fileToUpload">
            <?php 
            echo "<button class =\"sign-in-btn\" type=\"submit\" value=\"$userid\" name=\"userid\"> Upload</button>";
            ?>
           
        </div>
    </div>
    </form>

    <form action="home.php">
        <button class="home">Home</button>
    </form>
</html>