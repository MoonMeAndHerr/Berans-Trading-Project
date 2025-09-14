<?php

require_once __DIR__ . '/../../global/main_configuration.php';

session_start();

$db = openDB();

if (!isset($_GET['id']) || !isset($_GET['action'])) {
    die("Invalid request");
}

$id = (int) $_GET['id'];
$action = $_GET['action'];

// Fetch backup record
$stmt = $db->prepare("SELECT * FROM backup_history WHERE id = ?");
$stmt->execute([$id]);
$backup = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$backup) {
    die("Backup not found");
}

switch ($action) {
case "rollback":
    if ($backup['backup_type'] === 'database') {
        // LONGBLOB is already string, no need for stream_get_contents
        $sqlData = $backup['backup_file'];

        try {
            $db->exec("SET FOREIGN_KEY_CHECKS=0");

            // Drop all tables except backup_history
            $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_NUM);
            foreach ($tables as $row) {
                if ($row[0] !== 'backup_history') {
                    $db->exec("DROP TABLE IF EXISTS `{$row[0]}`");
                }
            }

            // Run SQL dump
            $db->exec($sqlData);

            $db->exec("SET FOREIGN_KEY_CHECKS=1");

            header("Location: ../private/logout.php");
        } catch (Exception $e) {
            $_SESSION['result'] = "❌ Database rollback failed: " . $e->getMessage();
            header("Location: ../public/database-backup.php");
        }

    } elseif ($backup['backup_type'] === 'website') {
        $zipData = $backup['backup_file']; // already string

        try {
            $tmpZip = tempnam(sys_get_temp_dir(), 'rollback_') . ".zip";
            file_put_contents($tmpZip, $zipData);

            $zip = new ZipArchive();
            if ($zip->open($tmpZip) === TRUE) {
                $projectRoot = realpath(__DIR__ . "/.."); // adjust if needed
                $zip->extractTo($projectRoot);
                $zip->close();
            } else {
                throw new Exception("Unable to open backup ZIP");
            }

            unlink($tmpZip);

            header("Location: ../private/logout.php");
        } catch (Exception $e) {
            $_SESSION['result'] = "❌ Website rollback failed: " . $e->getMessage();
            header("Location: ../public/website-backup.php");
        }
    }
    break;

case "download":
    $fileData = $backup['backup_file']; // use directly
    $fileName = "backup_" . $backup['backup_type'] . "_" . $backup['id'];

    if ($backup['backup_type'] === 'database') {
        header("Content-Type: application/sql");
        header("Content-Disposition: attachment; filename=\"$fileName.sql\"");
    } else {
        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=\"$fileName.zip\"");
    }

    echo $fileData;
    break;

    case "delete":
        $stmt = $db->prepare("DELETE FROM backup_history WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['result'] = "✅ Backup deleted successfully.";
        header("Location: ../public/website-backup.php");
        break;

    default:
        $_SESSION['result'] = "❌ Invalid action.";
        header("Location: ../public/website-backup.php");
}
