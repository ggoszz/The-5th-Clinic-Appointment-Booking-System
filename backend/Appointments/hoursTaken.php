<?php
require '../db_connect.php';

// Example url structure:
// Appointments/getAppointment.php?Date=2023-10-01

// Get JSON input from frontend
header('Content-Type: application/json');
// Decode the JSON input
$data = json_decode(file_get_contents('php://input'), true);

$Date = $_GET['Date'];


// Prepare and bind
$stmt = $conn->prepare("SELECT * FROM appointments WHERE Date=?");
$stmt->bind_param("s", $Date);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $patients = [];
        while ($row = $result->fetch_assoc()) {
            $patients[] = $row["Time"];
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