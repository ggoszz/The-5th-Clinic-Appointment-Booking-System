<?php
header('Content-Type: application/json');
require __DIR__ . '/../db_connect.php';
// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // <- enable while debugging

$in = json_decode(file_get_contents('php://input'), true);
if (!$in) { echo json_encode(["success"=>false,"error"=>"Invalid JSON"]); exit; }

$oDoctorID = (int)($in['oDoctorID'] ?? 0);
$oPatientID = (int)($in['oPatientID'] ?? 0);
$oDate      = trim($in['oDate'] ?? '');
$oTime      = trim($in['oTime'] ?? '');

$DoctorID = (int)($in['DoctorID'] ?? 0);
$PatientID= (int)($in['PatientID'] ?? 0);
$Date     = trim($in['Date'] ?? '');
$Time     = trim($in['Time'] ?? '');
$Status   = array_key_exists('Status', $in) ? trim((string)$in['Status']) : null;
$Desc     = array_key_exists('Description', $in) ? trim((string)$in['Description']) : null;

if(!$oDoctorID || !$oPatientID || $oDate==='' || $oTime===''){
  echo json_encode(["success"=>false,"error"=>"Missing original key"]); exit;
}
if(!$DoctorID || !$PatientID || $Date==='' || $Time===''){
  echo json_encode(["success"=>false,"error"=>"Missing new values"]); exit;
}

// Optional: prevent collision if changing to a slot that already exists
$collide = $conn->prepare(
  "SELECT 1 FROM appointments
   WHERE DoctorID=? AND `Date`=? AND `Time`=?
     AND NOT (DoctorID=? AND PatientID=? AND `Date`=? AND `Time`=?)
   LIMIT 1"
);
// types: i s s i i s s
$collide->bind_param("issiiss", $DoctorID, $Date, $Time, $oDoctorID, $oPatientID, $oDate, $oTime);
$collide->execute(); $collide->store_result();
if ($collide->num_rows > 0) {
  echo json_encode(["success"=>false,"error"=>"That time slot is already taken for this doctor."]); exit;
}

// Build dynamic SET
$sets = ["DoctorID=?","PatientID=?","`Date`=?","`Time`=?"];
$params = [$DoctorID,$PatientID,$Date,$Time];
$types  = "iiss";

if ($Status !== null)     { $sets[] = "Status=?";      $params[]=$Status; $types.="s"; }
if ($Desc !== null)       { $sets[] = "Description=?"; $params[]=$Desc;   $types.="s"; }

$sql = "UPDATE appointments SET ".implode(',', $sets)." WHERE DoctorID=? AND PatientID=? AND `Date`=? AND `Time`=?";
$params = array_merge($params, [$oDoctorID,$oPatientID,$oDate,$oTime]);
$types .= "iiss";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
  echo json_encode(["success"=>true, "updated"=>$stmt->affected_rows]);
} else {
  echo json_encode(["success"=>false,"error"=>$stmt->error]);
}
