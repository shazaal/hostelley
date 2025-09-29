<?php
// ONLY include the database connection. Do NOT include any layout files.
include __DIR__ . '/sqlConnection.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $blockName = trim($_POST['blockName'] ?? '');

    if(empty($blockName)){
        echo "Error: Block name is required!";
        exit;
    }

    $stmt = $con->prepare("SELECT id FROM tb_block WHERE bl_name = ?");
    $stmt->bind_param("s", $blockName);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result && $result->num_rows > 0){
        echo "exists";
        exit;
    }
    $stmt->close();

    $stmt = $con->prepare("INSERT INTO tb_block (bl_name) VALUES (?)");
    $stmt->bind_param("s", $blockName);
    
    if($stmt->execute()){
        echo $con->insert_id; 
    } else {
        echo "Error: ".$con->error;
    }
    $stmt->close();
}
?>
