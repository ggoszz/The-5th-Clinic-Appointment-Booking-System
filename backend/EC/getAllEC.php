<?php
include '../db_connect.php';

$sql = "SELECT * FROM emergencycontacts";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data
    $emergencyContacts = [];
    while($row = $result->fetch_assoc()) {
        $emergencyContacts[] = $row;
    }
    echo json_encode($emergencyContacts);
} else {
    echo "No emergency contacts found.";
}

$conn->close();
?>
