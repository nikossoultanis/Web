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
    <link rel='icon' href='favicon.png' type='image/x-icon' />
    <?php
            $username_disp = $_SESSION['user']['username'];
            $userid = $_SESSION['user']['userid'];
            echo "<title>$username_disp</title>";
        ?>
</head>

<body>
    <div class="sign-in-form">
        <?php
            $username_disp = $_SESSION['user']['username'];
            $userid = $_SESSION['user']['userid'];
            echo "<h1>😃 $username_disp<h1>";
        ?>
        <form action="functions.php">
            <button class="parse-btn" type="submit" name="log-out-btn">👋 Log out</button>
        </form>

        <?php
            echo "
            <form method=\"post\" action=\"upload.php\">
            
                <button type=\"submit\" class=\"parse-btn\" name=\"userid\" value=\"$userid\">📤 Upload & Parse</button>
            </form>";
        ?>
        <form action="map.php">
            <button class="parse-btn" type="submit" >🗺️ Map</button>
        </form>
        <form action="dashboard.php">
            <button class="parse-btn" type="submit" >📈 Dashboard</button>
        </form>

        <?php
            if ($_SESSION['user']['admin'] == 1){
                echo "<button onclick=\"location.href='admin.php'\" type=\"button\" class=\"sign-in-btn\">🔑 Admin Dashboard</button>";
            }
        ?>

    </div>
</body>
</html>