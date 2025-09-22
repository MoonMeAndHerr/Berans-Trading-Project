<?php
require_once __DIR__ . '/../../global/main_configuration.php';

function backupSourceCode($projectDir, $triggeredBy) {
    $zipFile = tempnam(sys_get_temp_dir(), 'srcbackup_') . ".zip";
    $zip = new ZipArchive();
    if ($zip->open($zipFile, ZipArchive::CREATE) !== TRUE) {
        throw new Exception("Cannot create ZIP archive");
    }

    $projectDir = realpath($projectDir);

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($projectDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($files as $file) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($projectDir) + 1);
        if ($file->isDir()) {
            $zip->addEmptyDir($relativePath);
        } else {
            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();

    $zipData = file_get_contents($zipFile);
    unlink($zipFile);

    $db = openDB();
    try {
        $stmt = $db->prepare("INSERT INTO backup_history (backup_time, backup_type, triggered_by, status) VALUES (NOW(), 'website', ?, 'Started')");
        $stmt->execute([$triggeredBy]);
        $historyId = $db->lastInsertId();

        $stmt = $db->prepare("UPDATE backup_history SET status = 'Completed', backup_file = ? WHERE id = ?");
        $stmt->bindParam(1, $zipData, PDO::PARAM_LOB);
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
