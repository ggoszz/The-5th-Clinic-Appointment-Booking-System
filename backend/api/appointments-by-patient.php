<?php
header('Content-Type: application/json');
require __DIR__ . '/../db_connect.php';

// GET ?patientId=101 (or POST JSON, but GET is fine here)
$patientId = isset($_GET['patientId']) ? (int)$_GET['patientId'] : 0;
if (!$patientId) { echo json_encode(["success"=>false,"error"=>"patientId required"]); exit; }

$sql = "SELECT 
          a.DoctorID, a.PatientID, a.`Date`, a.`Time`, a.Status, a.Description,
          CONCAT(d.FirstName,' ',d.LastName) AS DoctorName,
          d.Specialty
        FROM appointments a
        JOIN doctors d ON d.DoctorID = a.DoctorID
        WHERE a.PatientID = ?
        ORDER BY a.`Date` DESC, a.`Time` DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patientId);
$stmt->execute();
$res = $stmt->get_result();

$rows = [];
while ($row = $res->fetch_assoc()) $rows[] = $row;

echo json_encode(["success"=>true, "appointments"=>$rows]);
