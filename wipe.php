<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

//$user_id = $_POST['user_id'];
$user_id = 30;


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mysales";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}else {


        
    $sql = "UPDATE risks SET pushed=1 WHERE user_id=$user_id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode('succes');
    } else {
        echo "Error updating record: " . $conn->error;
    }

 }
 $conn->close();
?>