<?php
require_once '../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$employee_id = $_POST['id_value'] ?? '';
$start_date = $_POST['start_date'] ?? '';

try {
    $sql = "DELETE FROM job_history WHERE employee_id = :employee_id AND start_date = :start_date";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':employee_id' => $employee_id,
        ':start_date' => $start_date
    ]);
    
    echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

