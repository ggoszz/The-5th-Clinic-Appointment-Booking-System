<?php
header('Content-Type: application/json');
require __DIR__ . '/../db_connect.php';

try {
  $patientId = isset($_GET['patientId']) ? (int)$_GET['patientId'] : 0;
  if (!$patientId) throw new Exception("Missing patientId");

  // If you DO have ContactID in table, include it in SELECT (recommended).
  // If you DON'T, just drop ContactID from the SELECT list.
  $sql = "SELECT PatientID, Name, Relationship, Phone, Email
            FROM emergencycontacts
           WHERE PatientID = ?
        ORDER BY Name ASC";

  $stmt = $conn->prepare($sql);
  if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);

  $stmt->bind_param("i", $patientId);
  if (!$stmt->execute()) throw new Exception("Execute failed: " . $stmt->error);

  $res = $stmt->get_result();
  if ($res === false) throw new Exception("get_result failed");

  $rows = $res->fetch_all(MYSQLI_ASSOC);
  echo json_encode(["success"=>true, "contacts"=>$rows]);
} catch (Throwable $e) {
  echo json_encode(["success"=>false, "error"=>$e->getMessage()]);
}
