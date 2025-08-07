<?php
require '../db_connect.php';


// Example POST structure: backend/EC/insertEC.php
/* Example JSON input structure:
 {
   "PatientID": 123,
   "Name": "Bruce Wayne",
   "Relationship": "Brother",
   "Phone": "987-654-3210",
   "Email": "bruce.wayne@example.com"
}
*/

// Get JSON input from frontend
header('Content-Type: application/json');
// Decode the JSON input
$data = json_decode(file_get_contents('php://input'), true);

$PatientID = $data['PatientID'];
$ECName = $data['Name'];
$Relationship = $data['Relationship'];
$Phone = $data['Phone'];
$Email = $data['Email'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO emergencycontacts (PatientID, Name, Relationship, Phone, Email) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $PatientID, $ECName, $Relationship, $Phone, $Email);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Emergency contact added successfully."]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>