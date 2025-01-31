<?php

require __DIR__ . '/vendor/autoload.php';
$servername = "localhost";
$username = "root";
$password = "";
$dbname =  "data";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    echo "Failed";
}
$userid = $_POST['userid'];

echo $userid;


$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
if($target_file) {

    if ($uploadOk == 0) {
        $_FILES["error"] = "Sorry, your file was not uploaded.";
        header('location: upload.php');

    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
            exit;        }
        }
}


$decoded = \JsonMachine\JsonMachine::fromFile($target_file, '/locations');



// foreach ($decoded as $locations)
// {
    foreach($decoded as $item)
    {
        $heading = 0;
        $activity_type = 0;
        $activity_confidence = 0;
        $activity_timestamp = 0;
        $verticalAccuracy = 0;
        $velocity = 0;
        $accuracy = 0;
        $longitude = 0;
        $latitude = 0;
        $altitude = 0;
        $timestamp = 0;
        $E7 = 10**7;
        $timestamp = $item["timestampMs"];
        $latitude = $item["latitudeE7"] / $E7;
        $longitude = $item["longitudeE7"] / $E7;
        $accuracy = $item["accuracy"];

    if(acos(sin($latitude*0.0175)* sin(38.230462 * 0.0175) 
    + cos($latitude * 0.0175) * cos(38.230462*0.0175) * 
    cos((21.753150 * 0.0175) - ($longitude * 0.0175)))*6371 >= 10)
    {
        continue;
    }
    if(!empty($item["velocity"])){
        $velocity = $item["velocity"];
    }

    if(!empty($item["heading"])){
        $heading = $item["heading"];
    }

    if(!empty($item["altitude"])){
        $altitude = $item["altitude"];
    } 
    
    if (!empty($item["verticalAccuracy"])){
        $verticalAccuracy = $item["verticalAccuracy"];
    }

    if(!empty($item["activity"])){
        foreach($item["activity"] as $activity_item){
            $activity_timestamp = $activity_item["timestampMs"];
            foreach($activity_item["activity"] as $sub_activity){
                $activity_type = $sub_activity["type"];
                $activity_confidence = $sub_activity["confidence"];
                $sql = "INSERT INTO locations(userid, heading, activity_type, activity_confidence, activity_timestamp, vertical_accuracy, velocity, accuracy, longitude, latitude, altitude, timestamp) 
                VALUES ('$userid', $heading, '$activity_type', $activity_confidence, $activity_timestamp, $verticalAccuracy, $velocity, $accuracy, $longitude, $latitude, $altitude, '$timestamp')";
                if(mysqli_query($conn, $sql)){
                    //echo "Successfully Uploaded.";
                } else{
                    exit;
                }
            }
        }

    } else{
        $sql = "INSERT INTO locations(userid, heading, activity_type, activity_confidence, activity_timestamp, vertical_accuracy, velocity, accuracy, longitude, latitude, altitude, timestamp) 
        VALUES ('$userid', $heading, $activity_type, $activity_confidence, $activity_timestamp, $verticalAccuracy, $velocity, $accuracy, $longitude, $latitude, $altitude, '$timestamp')";
        if(mysqli_query($conn, $sql)){
            //echo "Successfully Uploaded.";
        } else{
            exit;
        }

    }

    }
// }
$query = "SELECT activity_type,  COUNT(*) AS count FROM locations WHERE userid = '$userid'";
$all_activities = mysqli_query($conn, $query);
$query2 = "SELECT activity_type,  COUNT(*) AS count FROM locations WHERE userid = '$userid' AND activity_type LIKE 'ON_%' OR activity_type LIKE 'RUN%' OR activity_type LIKE 'STI%' OR activity_type LIKE 'WALK%'";
$eco = mysqli_query($conn, $query2);
$all_activities = $all_activities->fetch_assoc();
$eco = $eco->fetch_assoc();
$all_activities = (int) $all_activities['count'];
$eco = (int) $eco['count'];
$ecolevel = round( $eco / $all_activities,2 ) * 100;
if($all_activities == 0)
{
    $ecolevel = 0;
}else{
$ecolevel = round( $eco / $all_activities,2 ) * 100;
}
echo $userid;
echo $ecolevel;
$delete_query = "DELETE FROM uploads
WHERE userid = '$userid';";
mysqli_query($conn, $delete_query);

$query3 = "INSERT INTO uploads ( userid, last_upload, score ) VALUES ( '$userid', NOW(), $ecolevel )";
mysqli_query($conn, $query3);
header('location: user.php');
exit;

?>