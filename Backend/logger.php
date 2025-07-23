<?php
function logDebug($message, $data = []) {
    $logFile = __DIR__ . '/debug.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message";
    
    if (!empty($data)) {
        $logMessage .= " - " . json_encode($data, JSON_PRETTY_PRINT);
    }
    
    $logMessage .= PHP_EOL;
    
    // Write to log file (append mode)
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}
?>