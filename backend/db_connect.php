<?php
$host = 'localhost'; // or 127.0.0.1
$dbname = 'clinicdatabase';
$username = 'root';
$password = ''; // default password in XAMPP is empty

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
