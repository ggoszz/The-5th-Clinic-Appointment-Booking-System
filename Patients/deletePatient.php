<?php
// Connect to DB
include '../db_connect.php';

// Example url structure:
// Patients/deletPatient.php?PatientID=123

if (isset($_GET['PatientID'])) {
    $PatientID = $_GET['PatientID'];

    // Prepare and execute DELETE query
    $stmt = $conn->prepare("DELETE FROM patients WHERE PatientID = ?");
    $stmt->bind_param("i", $PatientID);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Patient deleted successfully"]);
    } else {
        echo json_encode(["error" => "Failed to delete patient"]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Missing PatientID parameter"]);
}

$conn->close();
?>
