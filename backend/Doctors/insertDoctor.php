<?php
require '../db_connect.php';


// Example POST structure: backend/Doctors/insertDoctor.php
/* Example JSON input structure:
 {
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
$FirstName = $data['FirstName'];
$LastName = $data['LastName'];
$Email = $data['Email'];
$Phone = $data['Phone'];
$Specialty = $data['Specialty'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO doctors (FirstName, LastName, Email, Phone, Specialty) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $FirstName, $LastName, $Email, $Phone, $Specialty);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Doctor added successfully.", "DoctorID" => $conn->insert_id]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>