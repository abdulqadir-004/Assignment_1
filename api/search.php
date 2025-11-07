<?php
require_once '../config/database.php';
header('Content-Type: application/json');

$table = $_GET['table'] ?? '';
$search_term = $_GET['term'] ?? '';

$allowed_tables = ['regions', 'countries', 'locations', 'departments', 'jobs', 'employees', 'job_history'];

if (!in_array($table, $allowed_tables)) {
    echo json_encode(['error' => 'Invalid table name']);
    exit;
}

try {
    // Get column names for the table
    $stmt = $pdo->prepare("DESCRIBE $table");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Build search query
    $conditions = [];
    foreach ($columns as $column) {
        $conditions[] = "$column LIKE :search";
    }
    $where_clause = implode(' OR ', $conditions);
    
    $sql = "SELECT * FROM $table WHERE $where_clause";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':search' => "%$search_term%"]);
    $data = $stmt->fetchAll();
    
    echo json_encode(['success' => true, 'data' => $data]);
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

