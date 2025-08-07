<?php
include '../db_connect.php';

$sql = "SELECT * FROM patients";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data
    $patients = [];
    while($row = $result->fetch_assoc()) {
        $patients[] = $row;
    }
    echo json_encode($patients);
} else {
    echo "No patients found.";
}

$conn->close();
?>
