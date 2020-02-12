<?php
    include 'functions.php';
    if (!isset($_SESSION['user'])) {
        header('location: sign_in.php');
        exit;
    }
?>

<html>
    <head>    
    <?php
            $username_disp = $_SESSION['user']['username'];
            $userid = $_SESSION['user']['userid'];
            echo "<h1>😃 $username_disp<h1>";
        ?>
        <link rel="stylesheet" href="style.css" />
    </head>
    <form action="parser.php" method="POST" enctype="multipart/form-data">
    <div class="container">
        <div class="button-wrap">
            <label class ="new-button" for="upload"> Upload CV</label>
            <input id="upload" type="file" name="fileToUpload">
            <?php 
            echo "<button class =\"parse-btn\" type=\"submit\" value=\"$userid\" name=\"userid\">";
            ?>
            <!-- <input class ="upload-confirm" type="submit" value="Upload File" name="submit"> -->
        </div>
    </div>
    </form>
</html>