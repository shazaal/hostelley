<?php
header('Content-Type: application/json');
include __DIR__ . '/sqlConnection.php';

$block_id = $_GET['block_id'] ?? 0;
$block_id = (int)$block_id;

$rooms = [];
if ($block_id > 0) {
    $stmt = $con->prepare("SELECT id, room_no FROM tb_rooms WHERE block_id = ? ORDER BY id ASC");
    $stmt->bind_param("i", $block_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
    $stmt->close();
}

echo json_encode($rooms);
?>

