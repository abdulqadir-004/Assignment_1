<?php
require_once '../config/database.php';
header('Content-Type: application/json');

$table = $_GET['table'] ?? '';

$allowed_tables = ['regions', 'countries', 'locations', 'departments', 'jobs', 'employees', 'job_history'];

if (!in_array($table, $allowed_tables)) {
    echo json_encode(['error' => 'Invalid table name']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM $table");
    $stmt->execute();
    $data = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $data]);
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

