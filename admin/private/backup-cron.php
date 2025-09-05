<?php
require_once 'database-backup.php';
require_once 'website-backup.php';


if(isset($_GET['action'])){
    if($_GET['action'] === 'database-backup'){
        backupDatabase('manual');
        header("Location: ../public/database-backup.php");
    } elseif ($_GET['action'] === 'website-backup'){
        backupSourceCode($projectDir, 'manual');
    }
} else {
    backupDatabase('cron');
    backupSourceCode($projectDir, 'cron');
}

