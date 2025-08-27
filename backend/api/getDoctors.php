<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
require __DIR__ . '/../db_connect.php';

try {
  // Run query
  $sql = "SELECT DoctorID, CONCAT(FirstName,' ',LastName) AS Name, Specialty
          FROM doctors
          ORDER BY LastName, FirstName";
  $res = $conn->query($sql);
  if (!$res) {
    throw new Exception($conn->error ?: 'Query failed');
  }

  // Collect rows
  $doctors = [];
  while ($row = $res->fetch_assoc()) {
    $doctors[] = $row;
  }

  echo json_encode(["success" => true, "doctors" => $doctors], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
