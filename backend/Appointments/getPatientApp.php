<?php
require '../db_connect.php';

// Example url structure:
// Appointments/getAppointment.php?PatientID=123

// Get JSON input from frontend
header('Content-Type: application/json');
// Decode the JSON input
$data = json_decode(file_get_contents('php://input'), true);

$PatientID = $_GET['PatientID'];


// Prepare and bind
$stmt = $conn->prepare("SELECT * FROM appointments WHERE PatientID=?");
$stmt->bind_param("i", $PatientID);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $patients = [];
        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }
        echo json_encode(["success" => true, "data" => $patients]);
    } else {
        echo json_encode(["success" => false, "message" => "No appointments found."]);
    }
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>