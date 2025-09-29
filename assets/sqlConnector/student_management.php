<?php
include __DIR__ . '/sqlConnection.php';

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve POST data
    $student_name = $_POST['student_name'] ?? '';
    $contact_no = $_POST['contact_no'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $bed_id = $_POST['bed_id'] ?? '';

    // Basic validation
    if (empty($student_name) || empty($contact_no) || empty($bed_id) || !is_numeric($bed_id)) {
        die("Error: Please fill all required fields and select a valid bed.");
    }

    $bed_id = (int)$bed_id;

    // Start a transaction for safety
    $con->begin_transaction();
    try {
        // Step 1: Check if the bed is already occupied
        $stmt_check = $con->prepare("SELECT status FROM tb_bed WHERE id = ?");
        $stmt_check->bind_param("i", $bed_id);
        $stmt_check->execute();
        $bed_status = $stmt_check->get_result()->fetch_assoc()['status'];
        $stmt_check->close();

        if ($bed_status !== 'available') {
            throw new Exception("Error: This bed is already occupied.");
        }

        // Step 2: Insert the new student
        $stmt_insert = $con->prepare("INSERT INTO tb_student (student_name, contact_no, email, address, bed_id) VALUES (?, ?, ?, ?, ?)");
        $stmt_insert->bind_param("ssssi", $student_name, $contact_no, $email, $address, $bed_id);
        $stmt_insert->execute();
        $stmt_insert->close();

        // Step 3: Update the bed status to 'occupied'
        $stmt_update = $con->prepare("UPDATE tb_bed SET status = 'occupied' WHERE id = ?");
        $stmt_update->bind_param("i", $bed_id);
        $stmt_update->execute();
        $stmt_update->close();

        // If all steps are successful, commit the changes
        $con->commit();
        echo "Student added successfully!";

    } catch (Exception $e) {
        // If any step fails, roll back all changes
        $con->rollback();
        die($e->getMessage());
    }
}
?>
