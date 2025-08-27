<?php
header('Content-Type: application/json');
require __DIR__ . '/../db_connect.php';

/*
Returns all distinct patients who have (ever) had an appointment with the given doctor.
GET params: doctorId (required)
Optional: date=YYYY-MM-DD to only include patients with an appointment on that date.
*/

$doctorId = isset($_GET['doctorId']) ? (int)$_GET['doctorId'] : 0;
$date = isset($_GET['date']) ? trim($_GET['date']) : '';

if (!$doctorId) { echo json_encode(["success"=>false,"error"=>"doctorId required"]); exit; }

if ($date !== '') {
  $sql = "SELECT DISTINCT p.PatientID, p.FirstName, p.LastName, p.DOB, p.Gender,
                  p.Address, p.Phone, p.Email, p.InsuranceName, p.InsuranceID, NULL AS Note
          FROM patients p
          JOIN appointments a ON a.PatientID = p.PatientID
          WHERE a.DoctorID = ? AND a.Date = ?
          ORDER BY p.LastName, p.FirstName, p.PatientID";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("is", $doctorId, $date);
} else {
  $sql = "SELECT DISTINCT p.PatientID, p.FirstName, p.LastName, p.DOB, p.Gender,
                  p.Address, p.Phone, p.Email, p.InsuranceName, p.InsuranceID, NULL AS Note
          FROM patients p
          JOIN appointments a ON a.PatientID = p.PatientID
          WHERE a.DoctorID = ?
          ORDER BY p.LastName, p.FirstName, p.PatientID";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $doctorId);
}

if (!$stmt->execute()) { echo json_encode(["success"=>false,"error"=>$stmt->error]); exit; }
$res = $stmt->get_result();
$rows = [];
while ($r = $res->fetch_assoc()) { $rows[] = $r; }

echo json_encode(["success"=>true,"patients"=>$rows]);
