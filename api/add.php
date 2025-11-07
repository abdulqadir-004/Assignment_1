<?php
require_once '../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$table = $_POST['table'] ?? '';
$data = $_POST['data'] ?? [];

$allowed_tables = ['regions', 'countries', 'locations', 'departments', 'jobs', 'employees', 'job_history'];

if (!in_array($table, $allowed_tables)) {
    echo json_encode(['error' => 'Invalid table name']);
    exit;
}

try {
    // Parse JSON data if it's a string
    if (is_string($data)) {
        $data = json_decode($data, true);
    }
    
    // Build insert query dynamically
    $columns = array_keys($data);
    $values = array_values($data);
    $placeholders = ':' . implode(', :', $columns);
    $columns_str = implode(', ', $columns);
    
    $sql = "INSERT INTO $table ($columns_str) VALUES ($placeholders)";
    $stmt = $pdo->prepare($sql);
    
    // Bind parameters
    foreach ($data as $key => $value) {
        $stmt->bindValue(':' . $key, $value);
    }
    
    $stmt->execute();
    echo json_encode(['success' => true, 'message' => 'Record added successfully', 'id' => $pdo->lastInsertId()]);
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

