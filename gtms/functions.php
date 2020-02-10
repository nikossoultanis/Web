<?php
session_start();

$srv = "localhost";
$usrnm = "root";
$pass = "";
$db =  "data";
// Create connection
$conn = mysqli_connect($srv, $usrnm, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    echo "Failed";
}


$username = "";
$email    = "";
$errors   = array(); 

if (isset($_POST['sign-up-btn'])) {
    sign_up();
}

function sign_up(){
    global $conn, $errors, $username, $email;

    $username    =  e($_POST['username']);
	$email       =  e($_POST['email']);
	$password1  =  e($_POST['password1']);
    $password2  =  e($_POST['password2']);
	$password = md5($password1); //encrypt the password before saving in the database
    $userid = md5($email.$password1);
	$query = "INSERT INTO users (userid, username, email, password, admin) 
        VALUES('$userid', '$username', '$email', '$password', 0)";
	mysqli_query($conn, $query);

	// get id of the created user
	$logged_in_user_id = mysqli_insert_id($conn);
	$_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
	$_SESSION['success']  = "You are now logged in";
    header('location: index.html');	
}
function getUserById($id){
	global $conn;
	$query = "SELECT * FROM users WHERE id=" . $id;
	$result = mysqli_query($conn, $query);

	$user = mysqli_fetch_assoc($result);
	return $user;
}

// escape string
function e($val){
	global $conn;
	return mysqli_real_escape_string($conn, trim($val));
}

function display_error() {
	global $errors;

	if (count($errors) > 0){
		echo '<div class="error">';
			foreach ($errors as $error){
				echo $error .'<br>';
			}
		echo '</div>';
	}
}

function isLoggedIn()
{
	if (isset($_SESSION['user'])) {
		return true;
	}else{
		return false;
	}
}
// LOG OUT

// LOGIN IN 

if (isset($_POST['sign-in-btn'])) {
	if (!isLoggedIn()){
		login();
	} else {
		session_destroy();
		unset($_SESSION['user']);
		header('location: user.html');
	}
}


// LOGIN USER
function login(){
	global $conn, $username, $errors;

	// grap form values
	$username = e($_POST['username']);
	$password = e($_POST['password']);

	$password = md5($password);
	$query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
	$results = mysqli_query($conn, $query);
	var_dump($results);

	if (mysqli_num_rows($results) == 1) { // user found
		// check if user is admin or user
		$logged_in_user = mysqli_fetch_assoc($results);
		if ($logged_in_user['admin'] == 1) {
			$_SESSION['user'] = $logged_in_user;
			$_SESSION['success']  = "You are now logged in";
			//header('location: admin.html');		  
		}else{
			$_SESSION['user'] = $logged_in_user;
			$_SESSION['success']  = "You are now logged in";
			echo "user";
			header('location: user.html');
		}
	}else {
		array_push($errors, "Wrong username/password combination");
		header('location: sign_in.html');
	}
}


?>