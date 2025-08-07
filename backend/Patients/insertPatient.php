<?php
require '../db_connect.php';

// Example JSON input structure:
/*
{
   "FirstName": "John",
   "LastName": "Doe",
   "Email": "john.doe@example.com",
   "DOB": "1990-01-01",
   "Phone": "123-456-7890",
   "Address": "123 Main St",
   "Gender": "Male",
   "InsuranceName": "Health Insurance",
   "InsuranceID": "INS123"
}
*/

// Get JSON input from frontend
header('Content-Type: application/json');
// Decode the JSON input
$data = json_decode(file_get_contents('php://input'), true);

$FirstName = $data['FirstName'];
$LastName = $data['LastName'];
$Email = $data['Email'];
$Phone = $data['Phone'];
$DOB = $data['DOB'];
$Address = $data['Address'];
$Gender = $data['Gender'];
$InsuaranceName = $data['InsuranceName'];
$InsuaranceID = $data['InsuranceID'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO patients (FirstName, LastName, Email, Phone, DOB, Address, Gender, InsuaranceName, InsuaranceID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssss", $FirstName, $LastName, $Email, $Phone, $DOB, $Address, $Gender, $InsuaranceName, $InsuaranceID);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Patient added successfully.", "PatientID" => $conn->insert_id]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>