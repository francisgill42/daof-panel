<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$user_id = $_POST['user_id'];



$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mysales";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}else {


        
    // $sql = "UPDATE risks SET pushed=1 WHERE user_id=$user_id";
    $sql = "UPDATE risks SET pushed=1 WHERE user_id=$user_id";

    if ($conn->query($sql) === TRUE) {
         echo "updated record";
    } else {
        echo "Error updating record: " . $conn->error;
    }

 }
 $conn->close();
?>