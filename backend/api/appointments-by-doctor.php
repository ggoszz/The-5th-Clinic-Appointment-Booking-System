<?php
header('Content-Type: application/json');
require __DIR__ . '/../db_connect.php';

try {
  $doctorId = isset($_GET['doctorId']) ? (int)$_GET['doctorId'] : 0;
  if (!$doctorId) throw new Exception("Missing doctorId");

  // Optional date filter (YYYY-MM-DD) for "Today" button
  $dateFilter = isset($_GET['date']) ? trim($_GET['date']) : '';

  $sql = "SELECT a.DoctorID,
                 a.PatientID,
                 p.FirstName, p.LastName,
                 a.Date, a.Time, a.Status, a.Description
            FROM appointments a
            JOIN patients p ON p.PatientID = a.PatientID
           WHERE a.DoctorID = ? " . ($dateFilter !== '' ? " AND a.Date = ? " : "") . "
        ORDER BY a.Date, a.Time";

  $stmt = $conn->prepare($sql);
  if ($dateFilter !== '') {
    $stmt->bind_param("is", $doctorId, $dateFilter);
  } else {
    $stmt->bind_param("i", $doctorId);
  }
  $stmt->execute();
  $res = $stmt->get_result();

  $rows = [];
  while ($r = $res->fetch_assoc()) {
    $r['PatientName'] = trim(($r['FirstName'] ?? '') . ' ' . ($r['LastName'] ?? ''));
    unset($r['FirstName'], $r['LastName']);
    // Ensure HH:MM:SS for Time
    if (isset($r['Time']) && strlen($r['Time']) === 5) $r['Time'] .= ':00';
    $rows[] = $r;
  }

  echo json_encode(["success"=>true, "appointments"=>$rows]);
} catch (Throwable $e) {
  echo json_encode(["success"=>false, "error"=>$e->getMessage()]);
}
