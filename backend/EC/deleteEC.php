<?php
// Connect to DB
include '../db_connect.php';

// Example url structure:
// EC/deleteEC.php?PatientID=123&Name=Bruce Wayne

if (isset($_GET['PatientID']) && isset($_GET['Name'])) {
    $PatientID = $_GET['PatientID'];
    $Name = $_GET['Name'];

    // Prepare and execute DELETE query
    $stmt = $conn->prepare("DELETE FROM emergencycontacts WHERE PatientID = ? AND Name = ?");
    $stmt->bind_param("is", $PatientID, $Name);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Emergency contact deleted successfully"]);
    } else {
        echo json_encode(["error" => "Failed to delete emergency contact"]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Missing PatientID or Name parameter"]);
}

$conn->close();
?>
