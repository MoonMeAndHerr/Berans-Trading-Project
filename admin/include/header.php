<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../global/main_configuration.php';
require_once __DIR__ . '/../private/auth_check.php';

?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">

<head>

    <meta charset="utf-8" />
    <title><?php echo WEB_NAME; ?> | <?php echo WEB_TAGLINE; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo WEB_TAGLINE; ?>" name="description" />
    <meta content="Berans" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="../../media/<?php echo $favicon; ?>">

    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- SweetAlert2 Css -->
    <link rel="stylesheet" href="assets/libs/sweetalert2/sweetalert2.min.css">
    <!-- NPM Css -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
     <!-- Select Css -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- DataTable Css -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Filepond css -->
    <link rel="stylesheet" href="assets/libs/filepond/filepond.min.css" type="text/css" />
    <link rel="stylesheet" href="assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css">
    <!-- Custom Profit & Loss Styles -->
    <link href="assets/css/profit-loss.css" rel="stylesheet" type="text/css" />
    

    

</head>



<body>

    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php 
            include __DIR__ . '/../include/sidebar.php'; 
        ?>
