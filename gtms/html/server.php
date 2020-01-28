<?php
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
echo "Connected successfully<br>";

$strJsonFileContents = file_get_contents("data.json");
$decoded = json_decode($strJsonFileContents);

$empty = empty($decoded->locations[3]->activity);
if($empty){
    echo "t";
}


foreach ($decoded->locations as $item)
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

    $timestamp = $item->timestampMs;
    $latitude = $item->latitudeE7;
    $longitude = $item->longitudeE7;
    $accuracy = $item->accuracy;

    if(6371*acos(cos(deg2rad(38.230462))*cos(deg2rad($latitude))*cos(deg2rad($longitude) - deg2rad(21.753150)) + sin(deg2rad(38.230462)))*sin(deg2rad($latitude)) >= 10){
        continue;
    }

    if(!empty($item->velocity)){
        $velocity = $item->velocity;
    }

    if(!empty($item->heading)){
        $heading = $item->heading;
    }

    if(!empty($item->altitude)){
        $altitude = $item->altitude;
    } 
    
    if (!empty($item->verticalAccuracy)){
        $verticalAccuracy = $item->verticalAccuracy;
    }

    if(!empty($item->activity)){
        foreach($item->activity as $activity_item){
            $activity_timestamp = $activity_item->timestampMs;
            foreach($activity_item->activity as $sub_activity){
                $activity_type = $sub_activity->type;
                $activity_confidence = $sub_activity->confidence;
                $sql = "INSERT INTO locations(userid, heading, activity_type, activity_confidence, activity_timestamp, vertical_accuracy, velocity, accuracy, longitude, latitude, altitude, timestamp) 
                VALUES ('0', $heading, '$activity_type', $activity_confidence, $activity_timestamp, $verticalAccuracy, $velocity, $accuracy, $longitude, $latitude, $altitude, '$timestamp')";
                if(mysqli_query($conn, $sql)){
                    echo "Successfully Uploaded.";
                } else{
                    echo $conn->error;
                    echo "Error on Upload.";
                }
            }
        }

    } else{
        $sql = "INSERT INTO locations(userid, heading, activity_type, activity_confidence, activity_timestamp, vertical_accuracy, velocity, accuracy, longitude, latitude, altitude, timestamp) 
        VALUES ('0', $heading, $activity_type, $activity_confidence, $activity_timestamp, $verticalAccuracy, $velocity, $accuracy, $longitude, $latitude, $altitude, '$timestamp')";
        if(mysqli_query($conn, $sql)){
            echo "Successfully Uploaded.";
        } else{
            echo $conn->error;
            echo "Error on Upload.";
        }

}

}


?>