<?php
    include 'functions.php';

    if (!isset($_SESSION['user'])) {
        header('location: sign_in.php');
        exit;
    }
?>

<html>
    <head>    
        <link rel="stylesheet" href="style.css" />
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
</html>