<?php
    session_start();
    if (isset($_SESSION['user'])) {
        echo session_status();
        header('location: user.php');
        exit;
    }
?>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In</title>
    <link rel="stylesheet" href="style.css" />
    <link rel='icon' href='favicon.png' type='image/x-icon' />
</head>

<body>
    <div class="sign-in-form">
        <h1>🌍 Sign In</h1>
        <form method="post" action="functions.php">
            <input type="username" name="username" class="input-box" placeholder="Username" />
            <input type="password" name="password" class="input-box" placeholder="Password" />
            <button type="submit" name="sign-in-btn" class="sign-in-btn">Sign In</button>
            <hr />

            <p class="or"> Go back to <a href="sign_up.php">Sign Up</a>
            </p>
        </form>
        <?php
            if (isset($_SESSION["error"])) {
                $error = $_SESSION["error"];
                echo "<div class=\"error\">$error</div>";
            }

        ?>
    </div>
</body>
</html>

<?php
    unset($_SESSION["error"]);
?>