<?php
include __DIR__ . '/sqlConnection.php';

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $bed_id = $_POST['bed_id'] ?? '';

    if (empty($bed_id) || !is_numeric($bed_id)) {
        die("Error: Invalid Bed ID.");
    }

    $con->begin_transaction();
    try {
        // First, get room_id and block_id before deleting the bed
        $stmt = $con->prepare("SELECT r.id as room_id, r.block_id FROM tb_bed b JOIN tb_rooms r ON b.room_id = r.id WHERE b.id = ?");
        $stmt->bind_param("i", $bed_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) throw new Exception("Bed not found.");
        $ids = $result->fetch_assoc();
        $room_id = $ids['room_id'];
        $block_id = $ids['block_id'];

        // Now, delete the bed
        $stmt = $con->prepare("DELETE FROM tb_bed WHERE id = ?");
        $stmt->bind_param("i", $bed_id);
        $stmt->execute();

        // Update counts in tb_rooms and tb_block
        $stmt = $con->prepare("UPDATE tb_rooms SET capacity = capacity - 1 WHERE id = ?");
        $stmt->bind_param("i", $room_id);
        $stmt->execute ();

        $stmt = $con->prepare("UPDATE tb_block SET tn_bed = tn_bed - 1, tn_unoccupied = tn_unoccupied - 1 WHERE id = ?");
        $stmt->bind_param("i", $block_id);
        $stmt->execute();

        $con->commit();
        echo "Bed deleted successfully.";
    } catch (Exception $e) {
        $con->rollback(); 
        die("Error: " . $e->getMessage());
    }
}
?>
