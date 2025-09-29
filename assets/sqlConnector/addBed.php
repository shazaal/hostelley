<?php
// ONLY include the database connection.
include __DIR__ . '/sqlConnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = $_POST['room_id'] ?? '';
    $bed_count = $_POST['bed_count'] ?? '';

    if (empty($room_id) || !is_numeric($room_id) || empty($bed_count) || !is_numeric($bed_count) || $bed_count <= 0) {
        die("Error: Please select a valid room and enter a positive number of beds!");
    }

    $room_id = (int)$room_id;
    $bed_count = (int)$bed_count;

    $con->begin_transaction();
    try {
        // --- STEP 1: Get Block Name and Room Number ---
        $stmt = $con->prepare(
            "SELECT b.bl_name, r.room_no 
             FROM tb_rooms r 
             JOIN tb_block b ON r.block_id = b.id 
             WHERE r.id = ?"
        );
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) throw new Exception("Room not found.");
        
        $data = $result->fetch_assoc();
        $block_name = $data['bl_name'];
        // Extract only the number from the room_no (e.g., "R12" becomes "12")
        $room_number_int = (int)filter_var($data['room_no'], FILTER_SANITIZE_NUMBER_INT);
        // Format the room number to have leading zeros (e.g., 1 becomes 01, 12 becomes 12)
        $room_number_padded = str_pad($room_number_int, 2, '0', STR_PAD_LEFT);

        // Get the block_id for updating counts
        $stmt = $con->prepare("SELECT block_id FROM tb_rooms WHERE id = ?");
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        $block_id = (int)$stmt->get_result()->fetch_assoc()['block_id'];


        // --- STEP 2: Update Table Counts ---
        $stmt = $con->prepare("UPDATE tb_rooms SET capacity = capacity + ? WHERE id = ?");
        $stmt->bind_param("ii", $bed_count, $room_id);
        $stmt->execute();

        $stmt = $con->prepare("UPDATE tb_block SET tn_bed = tn_bed + ?, tn_unoccupied = tn_unoccupied + ? WHERE id = ?");
        $stmt->bind_param("iii", $bed_count, $bed_count, $block_id);
        $stmt->execute();

        // --- STEP 3: Get Last Bed Number ---
        $stmt = $con->prepare("SELECT COUNT(*) as cnt FROM tb_bed WHERE room_id = ?");
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        $start = $stmt->get_result()->fetch_assoc()['cnt'] + 1;

        // --- STEP 4: Insert New Beds with the Generated Student ID ---
        $bed_stmt = $con->prepare("INSERT INTO tb_bed (room_id, nu_bed, status, student_id) VALUES (?, ?, 'available', ?)");
        for ($i = $start; $i < $start + $bed_count; $i++) {
            $bed_no = "B" . $i;
            // Generate the student ID, e.g., A + 01 + 1 = A011
            $student_id = $block_name . $room_number_padded . $i;
            
            $bed_stmt->bind_param("iss", $room_id, $bed_no, $student_id);
            $bed_stmt->execute();
        }

        $con->commit();
        echo "Beds added successfully!";
    } catch (Exception $e) {
        $con->rollback();
        die("Transaction failed: " . $e->getMessage());
    }
}
?>
