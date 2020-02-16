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
        $userid = $_SESSION['user']['userid'];
        $query = "SELECT activity_type,  COUNT(*) AS count FROM locations WHERE userid = '$userid'";
        $username_disp = $_SESSION['user']['username'];
        $score_query = "SELECT score, last_upload FROM uploads WHERE userid='$userid'";
        $result_score = mysqli_query($conn, $score_query);
        $result_score = $result_score->fetch_assoc();
        echo "<title>$username_disp</title>";

        $top_user = array();
        $score = $result_score['score'];
        $upload = $result_score['last_upload'];
        $query = "SELECT * FROM `uploads` ORDER BY score DESC LIMIT 3";
        $top = mysqli_query($conn, $query);
        $i = 0;
        while($user = $top->fetch_assoc()) {
            $top_user[$i]['score'] = $user['score'];
            $tmp_uid = $user['userid'];
            $query = "SELECT username FROM `users` WHERE userid ='$tmp_uid'";
            $res_name = mysqli_query($conn, $query);
            $res_name = $res_name->fetch_assoc();            
            $top_user[$i]['name'] = $res_name['username'];
            $i++;
        }
        ?>
</head>

<body>



    <div class="sign-in-form">
        <?php
            $username_disp = $_SESSION['user']['username'];
            $userid = $_SESSION['user']['userid'];
            echo "<h1>😃 $username_disp <br>💚  $score%  <h1>";
            echo "<p class = \"last-upload\">Last Upload: $upload  </p>";
            echo "<hr>";
            echo "<p class = \"last-upload\"> Top Users </p>";

            for ($i = 1; $i <= sizeof($top_user); $i++) {
                echo "<p class = \"last-upload\">🏅 " . $top_user[$i - 1]['name'] . " - ".$top_user[$i - 1]['score'] . "</p>";
            }

        ?>
        <hr>
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