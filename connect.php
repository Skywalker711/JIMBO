<?php

$servername = "localhost";
$usernamesev = "Bithia";
$passwordsev = "pbandj";
$dbname = "Bithia";

// Create connection

$conn = new mysqli($servername, $usernamesev, $passwordsev, $dbname);

// Check connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}    

?>