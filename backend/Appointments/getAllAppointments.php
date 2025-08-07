<?php
include '../db_connect.php';

$sql = "SELECT * FROM appointments";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data
    $appointments = [];
    while($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
    echo json_encode($appointments);
} else {
    echo "No appointments found.";
}

$conn->close();
?>
