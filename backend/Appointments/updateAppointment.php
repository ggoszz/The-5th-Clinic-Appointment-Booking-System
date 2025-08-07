<?php
require '../db_connect.php';


// Example POST structure: backend/Doctors/insertDoctor.php
/* Example JSON input structure:
 {
    "DoctorID": 110,
    "PatientID": 123,
    "Date": "2023-10-01",
    "Time": "10:00:00",
    "Status": "Scheduled",
    "Description": "Initial consultation"
}
*/

// Get JSON input from frontend
header('Content-Type: application/json');
// Decode the JSON input
$data = json_decode(file_get_contents('php://input'), true);

$DoctorID = $data['DoctorID'];
$PatientID = $data['PatientID'];
$Date = $data['Date'];
$Time = $data['Time'];
$Status = $data['Status'];
$Description = $data['Description'];

// Prepare and bind
$stmt = $conn->prepare("UPDATE appointments SET Date = ?, Time = ?, Status = ?, Description = ? WHERE DoctorID = ? AND PatientID = ?");
$stmt->bind_param("ssssii", $Date, $Time, $Status, $Description, $DoctorID, $PatientID);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Appointment updated successfully."]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>