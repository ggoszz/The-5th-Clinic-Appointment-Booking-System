<?php
header('Content-Type: application/json');
require __DIR__ . '/../db_connect.php';

$in = json_decode(file_get_contents('php://input'), true);
if (!$in) { echo json_encode(["success"=>false,"error"=>"Invalid JSON"]); exit; }

$FirstName = trim($in['FirstName'] ?? '');
$LastName  = trim($in['LastName'] ?? '');
$DOB       = trim($in['DOB'] ?? null);     // YYYY-MM-DD
$Gender    = trim($in['Gender'] ?? '');
$Address   = trim($in['Address'] ?? '');
$Phone     = trim($in['Phone'] ?? '');
$Email     = trim($in['Email'] ?? '');
$InsuranceName = trim($in['InsuranceName'] ?? '');
$InsuranceID   = trim($in['InsuranceID'] ?? '');

if ($FirstName==='' || $LastName==='' || $Email==='') {
  echo json_encode(["success"=>false,"error"=>"FirstName, LastName, and Email are required."]); exit;
}

$stmt = $conn->prepare("
  INSERT INTO patients (FirstName, LastName, Email, DOB, Phone, Address, Gender, InsuranceName, InsuranceID)
  VALUES (?,?,?,?,?,?,?,?,?)
");
$stmt->bind_param("sssssssss",$FirstName,$LastName,$Email,$DOB,$Phone,$Address,$Gender,$InsuranceName,$InsuranceID);

if ($stmt->execute()) {
  echo json_encode(["success"=>true,"PatientID"=>$stmt->insert_id]);
} else {
  echo json_encode(["success"=>false,"error"=>$stmt->error]);
}
