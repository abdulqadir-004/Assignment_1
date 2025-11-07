<?php
require_once '../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$table = $_POST['table'] ?? '';
$id_field = $_POST['id_field'] ?? '';
$id_value = $_POST['id_value'] ?? '';

$allowed_tables = ['regions', 'countries', 'locations', 'departments', 'jobs', 'employees', 'job_history'];

if (!in_array($table, $allowed_tables)) {
    echo json_encode(['error' => 'Invalid table name']);
    exit;
}

try {
    // Build the delete query based on table
    $sql = "DELETE FROM $table WHERE $id_field = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id_value]);
    
    echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

