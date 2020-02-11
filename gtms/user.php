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

<body>
    <div class="sign-in-form">
        <?php
            $username_disp = $_SESSION['user']['username'];
            $userid = $_SESSION['user']['userid'];
            echo "<h1>$username_disp<h1>";
        ?>
        <form action="functions.php">
            <button class="parse-btn" type="submit" name="log-out-btn">Log out</button>
        </form>

        <?php
        echo "
            <form method=\"post\" action=\"parser.php\">
            
                <button type=\"submit\" class=\"parse-btn\" name=\"userid\" value=\"$userid\">Upload & Parse</button>
            </form>";
        ?>
        <form action="delete.php">
            <button class="parse-btn" onclick="return ('Are you sure confirm?');"> Delete </button>
        </form>
    </div>

</body>
</html>