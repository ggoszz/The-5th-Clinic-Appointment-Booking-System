<?php
require '../db_connect.php';

// Example url structure:
// backend/Doctors/getDoctor.php?

// Get JSON input from frontend
header('Content-Type: application/json');
// Decode the JSON input
$data = json_decode(file_get_contents('php://input'), true);

$firstName = $_GET['firstName'];
$lastName = $_GET['lastName'];

// Prepare and bind
$stmt = $conn->prepare("SELECT * FROM doctors WHERE FirstName=? AND LastName=?");
$stmt->bind_param("ss", $firstName, $lastName);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $doctors = [];
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }
        echo json_encode(["success" => true, "data" => $doctors]);
    } else {
        echo json_encode(["success" => false, "message" => "No doctor found."]);
    }
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>