<?php
// ONLY include the database connection.
include __DIR__ . '/sqlConnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $block_id = $_POST['block_id'] ?? '';
    $room_count = $_POST['room_count'] ?? '';

    if (empty($block_id) || !is_numeric($block_id) || $block_id == 0 || empty($room_count) || !is_numeric($room_count) || $room_count <= 0) {
        die("Error: Please select a valid block and enter a positive number of rooms!");
    }

    $block_id = (int)$block_id;
    $room_count = (int)$room_count;
    $beds_per_room = 4;
    $total_new_beds = $room_count * $beds_per_room;

    $con->begin_transaction();
    try {
        $stmt = $con->prepare("UPDATE tb_block SET nu_rooms = nu_rooms + ?, tn_bed = tn_bed + ?, tn_unoccupied = tn_unoccupied + ? WHERE id = ?");
        $stmt->bind_param("iiii", $room_count, $total_new_beds, $total_new_beds, $block_id);
        $stmt->execute();

        $stmt = $con->prepare("SELECT COUNT(*) as cnt FROM tb_rooms WHERE block_id = ?");
        $stmt->bind_param("i", $block_id);
        $stmt->execute();
        $start = $stmt->get_result()->fetch_assoc()['cnt'] + 1;

        $room_stmt = $con->prepare("INSERT INTO tb_rooms (block_id, room_no, capacity) VALUES (?, ?, ?)");
        $bed_stmt = $con->prepare("INSERT INTO tb_bed (room_id, nu_bed, status) VALUES (?, ?, 'available')");

        for ($i = $start; $i < $start + $room_count; $i++) {
            $room_no = "R" . $i;
            $room_stmt->bind_param("isi", $block_id, $room_no, $beds_per_room);
            $room_stmt->execute();
            $room_id = $con->insert_id;

            for ($j = 1; $j <= $beds_per_room; $j++) {
                $bed_no = "B" . $j;
                $bed_stmt->bind_param("is", $room_id, $bed_no);
                $bed_stmt->execute();
            }
        }

        $con->commit();
        echo "Rooms and beds added successfully!";
    } catch (Exception $e) {
        $con->rollback();
        die("Transaction failed: " . $e->getMessage());
    } 
}
?>
