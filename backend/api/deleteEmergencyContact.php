<?php
header('Content-Type: application/json');
require __DIR__ . '/../db_connect.php';

try {
  $in = json_decode(file_get_contents('php://input'), true);
  if (!$in) throw new Exception("Invalid JSON");

  $patientId = isset($in['PatientID']) ? (int)$in['PatientID'] : 0;
  $name = trim($in['Name'] ?? '');
  $phone = trim($in['Phone'] ?? '');

  if (!$patientId || $name === '' || $phone === '') {
    throw new Exception("Missing PatientID/Name/Phone");
  }

  $stmt = $conn->prepare("DELETE FROM emergencycontacts WHERE PatientID=? AND Name=? AND Phone=?");
  $stmt->bind_param("iss", $patientId, $name, $phone);

  if (!$stmt->execute()) throw new Exception("Delete failed: " . $stmt->error);
  echo json_encode(["success"=>true]);
} catch (Throwable $e) {
  echo json_encode(["success"=>false, "error"=>$e->getMessage()]);
}
