<?php
header('Content-Type: application/json');
require __DIR__ . '/../db_connect.php';

// NOTE: expect JSON body
$in = json_decode(file_get_contents('php://input'), true);
if (!$in) { echo json_encode(["success"=>false,"error"=>"Invalid JSON"]); exit; }

$DoctorID = (int)($in['DoctorID'] ?? 0);
$PatientID= (int)($in['PatientID']?? 0);
$Date     = trim($in['Date'] ?? '');
$Time     = trim($in['Time'] ?? '');
$Status   = trim($in['Status'] ?? 'Scheduled');
$Desc     = trim($in['Description'] ?? '');

if(!$DoctorID || !$PatientID || $Date==='' || $Time===''){
  echo json_encode(["success"=>false,"error"=>"DoctorID, PatientID, Date, and Time are required."]); exit;
}

// conflict: same doctor/date/time
$chk = $conn->prepare("SELECT 1 FROM appointments WHERE DoctorID=? AND `Date`=? AND `Time`=? LIMIT 1");
$chk->bind_param("iss", $DoctorID, $Date, $Time);
$chk->execute(); $chk->store_result();
if ($chk->num_rows > 0) {
  echo json_encode(["success"=>false,"error"=>"Time slot already taken for that doctor."]); exit;
}

// insert
$stmt = $conn->prepare("INSERT INTO appointments (DoctorID,PatientID,`Date`,`Time`,Status,Description)
                        VALUES (?,?,?,?,?,?)");
$stmt->bind_param("iissss", $DoctorID,$PatientID,$Date,$Time,$Status,$Desc);

if ($stmt->execute()) echo json_encode(["success"=>true]);
else echo json_encode(["success"=>false,"error"=>$stmt->error]);
