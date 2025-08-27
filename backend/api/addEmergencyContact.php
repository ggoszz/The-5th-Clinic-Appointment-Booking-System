<?php
header('Content-Type: application/json');
require __DIR__ . '/../db_connect.php';

try {
  $in = json_decode(file_get_contents('php://input'), true);
  if (!$in) throw new Exception("Invalid JSON");

  $PatientID = isset($in['PatientID']) ? (int)$in['PatientID'] : 0;
  $Name = trim($in['Name'] ?? '');
  $Relationship = trim($in['Relationship'] ?? '');
  $Phone = trim($in['Phone'] ?? '');
  $Email = trim($in['Email'] ?? '');

  if (!$PatientID || $Name === '' || $Relationship === '' || $Phone === '' || $Email === '') {
    throw new Exception("Missing required fields");
  }

  $stmt = $conn->prepare(
    "INSERT INTO emergencycontacts (PatientID, Name, Relationship, Phone, Email)
     VALUES (?, ?, ?, ?, ?)"
  );
  if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);

  $stmt->bind_param("issss", $PatientID, $Name, $Relationship, $Phone, $Email);
  if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);

  echo json_encode(["success" => true, "contactId" => $conn->insert_id]);
} catch (Throwable $e) {
  echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
