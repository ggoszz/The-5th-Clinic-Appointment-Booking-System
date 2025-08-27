<?php
header('Content-Type: application/json');
require '../db_connect.php';

// Expect JSON POST: { firstName, lastName, PatientID, DOB }
$in = json_decode(file_get_contents('php://input'), true);
if (!$in) { echo json_encode(["success"=>false,"error"=>"Invalid JSON"]); exit; }

$firstName = trim($in['firstName'] ?? '');
$lastName  = trim($in['lastName'] ?? '');
$PatientID = (int)($in['PatientID'] ?? 0);
$DOB       = trim($in['DOB'] ?? '');  // 'YYYY-MM-DD'

if ($firstName==='' || $lastName==='' || !$PatientID || $DOB==='') {
  echo json_encode(["success"=>false,"error"=>"All fields are required."]); exit;
}

$stmt = $conn->prepare("SELECT PatientID, FirstName, LastName, DOB FROM patients
                        WHERE FirstName=? AND LastName=? AND PatientID=? AND DOB=? LIMIT 1");
$stmt->bind_param("ssis", $firstName, $lastName, $PatientID, $DOB);
// types: s (first), s (last), i (PatientID), s (DOB)

if (!$stmt->execute()) {
  echo json_encode(["success"=>false,"error"=>$stmt->error]); exit;
}
$res = $stmt->get_result();

if ($res->num_rows === 1) {
  $row = $res->fetch_assoc();
  echo json_encode(["success"=>true,"patient"=>$row]);
} else {
  echo json_encode(["success"=>false,"message"=>"No patient found."]);
}
