<?php
/**
 * Common functions for table pages
 */

function getTableData($pdo, $table) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM $table");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        return [];
    }
}

function getTableColumns($pdo, $table) {
    try {
        $stmt = $pdo->prepare("DESCRIBE $table");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch(PDOException $e) {
        return [];
    }
}

function getPrimaryKey($pdo, $table) {
    try {
        $stmt = $pdo->prepare("SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ? $result['Column_name'] : null;
    } catch(PDOException $e) {
        return null;
    }
}
?>

