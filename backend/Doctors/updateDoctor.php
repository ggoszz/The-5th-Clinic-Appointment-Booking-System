<?php
require '../db_connect.php';


// Example POST structure: backend/Doctors/updateDoctor.php
/* Example JSON input structure:
 {
   "DoctorID": 110,
   "FirstName": "Dr.",
   "LastName": "House",
   "Email": "Wilson@example.com",
   "Phone": "123-456-7890",
   "Specialty": "Cardiology"
}
*/

// Get JSON input from frontend
header('Content-Type: application/json');
// Decode the JSON input
$data = json_decode(file_get_contents('php://input'), true);

// DoctorID, FirstName, LastName, Email, Phone, Specialty
$DoctorID = $data['DoctorID'];
$FirstName = $data['FirstName'];
$LastName = $data['LastName'];
$Email = $data['Email'];
$Phone = $data['Phone'];
$Specialty = $data['Specialty'];

// Prepare and bind
$stmt = $conn->prepare("UPDATE doctors SET FirstName = ?, LastName = ?, Email = ?, Phone = ?, Specialty = ? WHERE DoctorID = ?");
$stmt->bind_param("sssssi", $FirstName, $LastName, $Email, $Phone, $Specialty, $DoctorID);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Doctor updated successfully."]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>