<?php
    include 'functions.php';
      if ($_SESSION['user']['admin'] != 1){
        header('location: user.php');
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
            echo "<title>$username_disp</title>";
        ?>
</head>

<body>
        <form action="home.php">
            <button class="home" action="">Home</button>
        </form>
    <div class="sign-in-form">
        <?php
            $username_disp = $_SESSION['user']['username'];
            $userid = $_SESSION['user']['userid'];
            echo "<h1>🔑 $username_disp<h1>";
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
        <form action="delete.php">
            <button class="parse-btn" onclick="return confirm('E!?');" formaction="delete.php">🗑️ Delete</button>

            <button class="parse-btn" type="submit" formaction="export.php">📁 Export</button>

            <button class="parse-btn" type="submit" formaction="map.php" >🗺️ Map</button>

            <button class="parse-btn" type="submit" formaction="dashboard.php" >📈 Dashboard</button>

            <button class="parse-btn" type="submit" formaction="user.php">👤 User Mode</button>
        </form>

    </div>

</body>
</html>
