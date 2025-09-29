<?php
header('Content-Type: application/json');
include __DIR__ . '/sqlConnection.php';

$room_id = $_GET['room_id'] ?? 0;
$room_id = (int)$room_id;

$beds = [];
if ($room_id > 0) {
    // Only select beds that are 'available'
    $stmt = $con->prepare("SELECT id, nu_bed, student_id FROM tb_bed WHERE room_id = ? AND status = 'available' ORDER BY id ASC");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $beds[] = $row;
    }
    $stmt->close();
}

echo json_encode($beds);
?>

