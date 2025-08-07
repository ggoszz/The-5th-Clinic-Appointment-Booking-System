<?php
require '../db_connect.php';

// Example url structure:
// Patients/patientLogin.php?firstName=John&lastName=Doe&PatientID=123&DOB=1990-01-01

// Get JSON input from frontend
header('Content-Type: application/json');
// Decode the JSON input
$data = json_decode(file_get_contents('php://input'), true);

$firstName = $_GET['firstName'];
$lastName = $_GET['lastName'];
$PatientID = $_GET['PatientID'];
$DOB = $_GET['DOB'];

// Prepare and bind
$stmt = $conn->prepare("SELECT * FROM patients WHERE FirstName=? AND LastName=? AND PatientID=? AND DOB=?");
$stmt->bind_param("ssii", $firstName, $lastName, $PatientID, $DOB);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $patients = [];
        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }
        echo json_encode(["success" => true, "data" => $patients]);
    } else {
        echo json_encode(["success" => false, "message" => "No patient found."]);
    }
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>