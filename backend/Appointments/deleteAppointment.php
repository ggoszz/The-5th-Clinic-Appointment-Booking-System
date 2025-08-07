<?php
// Connect to DB
include '../db_connect.php';

// Example url structure for GET:
// Doctors/deleteDoctor.php?DoctorID=123&PatientID=456&Date=2023-10-01&Time=10:00:00

if (isset($_GET['DoctorID']) && isset($_GET['PatientID']) && isset($_GET['Date']) && isset($_GET['Time'])) {
    $DoctorID = $_GET['DoctorID'];
    $PatientID = $_GET['PatientID'];
    $Date = $_GET['Date'];
    $Time = $_GET['Time'];

    // Prepare and execute DELETE query
    $stmt = $conn->prepare("DELETE FROM appointments WHERE DoctorID = ? AND PatientID = ? AND Date = ? AND Time = ?");
    $stmt->bind_param("iiss", $DoctorID, $PatientID, $Date, $Time);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Appointment deleted successfully"]);
    } else {
        echo json_encode(["error" => "Failed to delete appointment"]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Missing parameters"]);
}

$conn->close();
?>
