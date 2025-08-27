<?php
header('Content-Type: application/json');
require __DIR__ . '/../db_connect.php';

$in = json_decode(file_get_contents('php://input'), true);
$DoctorID = (int)($in['DoctorID'] ?? 0);
$PatientID = (int)($in['PatientID'] ?? 0);
$Date = $in['Date'] ?? '';
$Time = $in['Time'] ?? '';

if(!$DoctorID || !$PatientID || $Date==='' || $Time===''){
  echo json_encode(["success"=>false,"error"=>"Missing key fields"]); exit;
}

$stmt = $conn->prepare("DELETE FROM appointments WHERE DoctorID=? AND PatientID=? AND `Date`=? AND `Time`=?");
$stmt->bind_param("iiss", $DoctorID, $PatientID, $Date, $Time);

if ($stmt->execute()) {
  echo json_encode(["success"=>true, "deleted"=>$stmt->affected_rows]);
} else {
  echo json_encode(["success"=>false,"error"=>$stmt->error]);
}
