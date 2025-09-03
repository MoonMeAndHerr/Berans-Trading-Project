<?php
session_start();
require_once __DIR__ . '/../../global/main_configuration.php';

function backupSourceCode($rootPath, $triggeredBy) {
    $db = openDB();

    // Create zip archive in memory
    $zip = new ZipArchive();
    $tmpZip = tempnam(sys_get_temp_dir(), 'webbackup_') . ".zip";
    if ($zip->open($tmpZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        throw new Exception("Cannot create ZIP file.");
    }

    $rootPath = realpath($rootPath);

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($files as $file) {
        $filePath = realpath($file);
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // 🚫 Exclude some sensitive folders/files if needed
        if (strpos($relativePath, 'backup_history') !== false) continue;
        if (strpos($relativePath, '.git') !== false) continue;

        if (is_dir($filePath)) {
            $zip->addEmptyDir($relativePath);
        } else {
            $zip->addFile($filePath, $relativePath);
        }
    }

    $zip->close();

    $zipData = file_get_contents($tmpZip);
    unlink($tmpZip);

    // Insert into DB
    $stmt = $db->prepare("INSERT INTO backup_history 
        (backup_type, backup_file, created_at, is_auto) 
        VALUES ('website', ?, NOW(), 0)");
    $stmt->bindParam(1, $zipData, PDO::PARAM_LOB);
    $stmt->execute();

    $_SESSION['result'] = "Website backup created successfully.";
    header("Location: ../public/website-backup.php");
}


