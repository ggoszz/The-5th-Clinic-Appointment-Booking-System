<?php
include '../db_connect.php';

$sql = "SELECT * FROM doctors";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data
    $doctors = [];
    while($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
    echo json_encode($doctors);
} else {
    echo "No doctors found.";
}

$conn->close();
?>
