<?php include __DIR__ . '/../private/staff-add-backend.php';?>


        <?php 
            include __DIR__ . '/../include/header.php'; 
        ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Website Backup</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Backup</a></li>
                                            <li class="breadcrumb-item active">Manage Backup</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->

            <div class="card mt-1 shadow">
                <div class="card-header d-flex align-items-center mx-3 mt-2">
                    <h3 class="card-title mb-0 me-3" style="white-space: nowrap;">Website Backup List</h3><a href="../private/backup-cron.php?action=website-backup" target="_blank" class="btn btn-sm btn-primary">Backup Now</a>
                </div>

                <!-- ✅ SHOW DELETE MESSAGE -->
                <?php 
                
                    if (isset($_SESSION['result'])){
                    
                ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($_SESSION['result']) ?>
                    </div>

                <?php

                    } unset($_SESSION['result']);

                ?>


                <!-- ✅ Add padding inside the card body -->
                <div class="card-body px-4 py-3">
                    <?php 

                        $pdo = openDB();
                        $stmt = $pdo->query("SELECT * FROM backup_history WHERE backup_type = 'website' ORDER BY backup_time DESC");
                    
                        
                    ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-0" id="staffTable">
                        <thead class="table-light">
                            <tr>
                            <th>Backup ID</th>
                            <th>Backup Time</th>
                            <th>Status</th>
                            <th>Triggered By</th>
                            <th>Error Message</th>
                            <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                                
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                            ?>
                            <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['backup_time']) ?></td>
                            <td><?= htmlspecialchars($row['status'] ?: '-') ?></td>
                            <td><?= htmlspecialchars($row['triggered_by'] ?: '-') ?></td>
                            <td><?= $row['error_message'] ?></td>
                            <td>
                                <a href="../private/backup-action?id=<?php echo $row['id']; ?>&action=rollback" target="_blank" class="btn btn-sm btn-success">Rollback</a>
                                <a href="../private/backup-action?id=<?php echo $row['id']; ?>&action=download" target="_blank" class="btn btn-sm btn-danger">Download</a>
                                <a href="../private/backup-action?id=<?php echo $row['id']; ?>&action=delete" class="btn btn-sm btn-warning">Delete</a>
                            </td>
                            </tr>
                            <?php 
                            }
                            ?>
                        </tbody>
                        </table>
                    </div>

                </div>
            </div>
  
                    </div> <!-- container-fluid -->           
                </div><!-- End Page-content -->
            <?php include __DIR__ . '/../include/footer.php';?>
        </div><!-- end main content-->
    </div><!-- END layout-wrapper -->
    

    <?php include __DIR__ . '/../include/themesetting.php';?>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>

    <script src="assets/js/app.js"></script>
</body>

</html>