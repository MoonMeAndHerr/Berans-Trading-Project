<?php
session_start();
require_once __DIR__ . '/../../global/main_configuration.php';

function backupDatabase($triggeredBy) {
    $db = openDB();
    $dbName = $db->query("SELECT DATABASE()")->fetchColumn();

    $tables = [];
    $result = $db->query("SHOW TABLES");
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }

    $sqlScript = "-- Database Backup: $dbName --\n";
    $sqlScript .= "-- Created at: " . date("Y-m-d H:i:s") . " --\n\n";
    $sqlScript .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

    foreach ($tables as $table) {
        if ($table === 'backup_history') continue;

        $createTableStmt = $db->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
        $sqlScript .= "\n-- Table structure for `$table` --\n";
        $sqlScript .= "DROP TABLE IF EXISTS `$table`;\n";
        $sqlScript .= $createTableStmt["Create Table"] . ";\n\n";

        $rows = $db->query("SELECT * FROM `$table`");
        while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {
            $columns = array_map(fn($col) => "`$col`", array_keys($row));
            $values  = array_map(fn($val) => $db->quote($val), array_values($row));
            $sqlScript .= "INSERT INTO `$table` (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ");\n";
        }
        $sqlScript .= "\n";
    }

    $sqlScript .= "SET FOREIGN_KEY_CHECKS=1;\n";

    try {
        // Insert history
        $stmt = $db->prepare("INSERT INTO backup_history (backup_time, backup_type, triggered_by, status) VALUES (NOW(), 'database', ?, 'Started')");
        $stmt->execute([$triggeredBy]);
        $historyId = $db->lastInsertId();

        // Save blob
        $stmt = $db->prepare("UPDATE backup_history SET status = 'Completed', backup_file = ? WHERE id = ?");
        $stmt->bindParam(1, $sqlScript, PDO::PARAM_LOB);
        $stmt->bindParam(2, $historyId, PDO::PARAM_INT);
        $stmt->execute();

        return true;

    } catch (Exception $e) {
        if (isset($historyId)) {
            $stmt = $db->prepare("UPDATE backup_history SET status = 'Failed', error_message = ? WHERE id = ?");
            $stmt->execute([$e->getMessage(), $historyId]);
        }
        return false;
    }
}
