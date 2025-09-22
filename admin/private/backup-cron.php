<?php
require_once 'database-backup.php';
require_once 'website-backup.php';


if(isset($_GET['action'])){
    if($_GET['action'] === 'database-backup'){
        backupDatabase('manual');
        exit();
    } elseif ($_GET['action'] === 'website-backup'){
        backupSourceCode(__DIR__, 'manual');
        exit();
    }
} else {
    backupDatabase('cron');
    backupSourceCode(__DIR__, 'cron');
    exit();
}

