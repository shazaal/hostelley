<?php
include __DIR__ . '/sqlConnection.php';

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $block_id = $_POST['block_id'] ?? '';

    if (empty($block_id) || !is_numeric($block_id)) {
        die("Error: Invalid Block ID.");
    }

    $stmt = $con->prepare("DELETE FROM tb_block WHERE id = ?");
    $stmt->bind_param("i", $block_id);

    if ($stmt->execute()) {
        echo "Block and all its rooms/beds have been deleted successfully.";
    } else {
        echo "Error: Failed to delete block.";
    }
    $stmt->close();
}
?>
