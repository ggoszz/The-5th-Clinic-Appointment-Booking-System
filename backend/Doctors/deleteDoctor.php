<?php
// Connect to DB
include '../db_connect.php';

// Example url structure for GET:
// Doctors/deleteDoctor.php?DoctorID=123

if (isset($_GET['DoctorID'])) {
    $DoctorID = $_GET['DoctorID'];

    // Prepare and execute DELETE query
    $stmt = $conn->prepare("DELETE FROM doctors WHERE DoctorID = ?");
    $stmt->bind_param("i", $DoctorID);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Doctor deleted successfully"]);
    } else {
        echo json_encode(["error" => "Failed to delete doctor"]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Missing DoctorID parameter"]);
}

$conn->close();
?>
