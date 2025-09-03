<?php
require_once 'database-backup.php';
require_once 'website-backup.php';

$projectDir = "C:\laragon\www\Work Related\Berans-Trading-Project";

if(isset($_GET['action'])){
    if($_GET['action'] === 'database-backup'){
        backupDatabase('manual');
    } elseif ($_GET['action'] === 'website-backup'){
        backupSourceCode($projectDir, 'manual');
    }
} else {
    backupDatabase('cron');
    backupSourceCode($projectDir, 'cron');
}

