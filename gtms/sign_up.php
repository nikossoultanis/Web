<?php
    session_start();
?>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css" />
    <link rel='icon' href='favicon.png' type='image/x-icon' />
</head>

<body>
    <div class="sign-up-form">
        <h1>🧭 Sign Up</h1>
        <form method="post" action="functions.php">
            <input type="username" id="username" name="username" required class="input-box" placeholder="Username" />
            <input type="email" id="email" name="email" required class="input-box" placeholder="Email" />
            <input type="password" id="password" name="password1" required class="input-box" placeholder="Password" />
            <input type="password" id="password" name="password2" required class="input-box" placeholder="Repeat Password" />
            <button type="submit" name="sign-up-btn" class="sign-up-btn">Sign Up</button>
            <hr />
            <p class="or">Do you already have an account?


                <a href="sign_in.php">Sign In</a
          >
        </p>
      </form>
      
      <?php 
        if (isset($_SESSION["error"])){   
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