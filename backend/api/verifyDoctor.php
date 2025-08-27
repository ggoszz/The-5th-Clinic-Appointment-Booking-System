<?php
header('Content-Type: application/json');
require __DIR__ . '/../db_connect.php';

try {
  $in = json_decode(file_get_contents('php://input'), true);
  if (!$in) throw new Exception("Invalid JSON");

  $DoctorID = isset($in['DoctorID']) ? (int)$in['DoctorID'] : 0; // the ID the doctor typed
  $Name = trim($in['Name'] ?? '');

  if (!$DoctorID || $Name==='') throw new Exception("Missing DoctorID/Name");

  $stmt = $conn->prepare("SELECT DoctorID, CONCAT(FirstName, ' ', LastName) AS Name, Specialty
                          FROM doctors WHERE DoctorID = ? LIMIT 1");
  $stmt->bind_param("i", $DoctorID);
  $stmt->execute();
  $row = $stmt->get_result()->fetch_assoc();

  if (!$row) throw new Exception("Doctor ID not found");
  if (strcasecmp($row['Name'], $Name) !== 0) throw new Exception("Doctor ID and name do not match");

  echo json_encode(["success"=>true, "doctor"=>$row]);
} catch (Throwable $e) {
  echo json_encode(["success"=>false, "error"=>$e->getMessage()]);
}
