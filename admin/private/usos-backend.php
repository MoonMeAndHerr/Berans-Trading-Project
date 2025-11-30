<?php
// USOS Backend Handler
// Unit Systematic Ordering System

if(session_status() === PHP_SESSION_NONE) session_start();

// Set error reporting for JSON responses
if (isset($_GET['action']) || isset($_POST['action'])) {
    error_reporting(E_ERROR | E_PARSE);
    ini_set('display_errors', 0);
}

require_once __DIR__ . '/../../global/main_configuration.php';

$pdo = openDB();

// Get all USOS configurations
function getUsosConfigs() {
    global $pdo;
    $query = "
        SELECT 
            u.*,
            c.customer_name,
            c.customer_company_name
        FROM usos_config u
        LEFT JOIN customer c ON u.customer_id = c.customer_id
        WHERE u.deleted_at IS NULL
        ORDER BY u.created_at DESC
    ";
    
    try {
        $stmt = $pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error fetching USOS configs: " . $e->getMessage());
        return [];
    }
}

// Get single USOS configuration by ID
function getUsosById($usos_id) {
    global $pdo;
    $query = "
        SELECT 
            u.*,
            c.customer_name,
            c.customer_company_name
        FROM usos_config u
        LEFT JOIN customer c ON u.customer_id = c.customer_id
        WHERE u.usos_id = :usos_id AND u.deleted_at IS NULL
    ";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':usos_id', $usos_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error fetching USOS config: " . $e->getMessage());
        return null;
    }
}

// Get schedule for a USOS configuration
function getUsosSchedule($usos_id) {
    global $pdo;
    $query = "
        SELECT * FROM usos_schedule
        WHERE usos_id = :usos_id
        ORDER BY order_date ASC
    ";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':usos_id', $usos_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error fetching USOS schedule: " . $e->getMessage());
        return [];
    }
}

// AJAX Actions Handler
if (isset($_GET['action']) || isset($_POST['action'])) {
    $action = $_GET['action'] ?? $_POST['action'];
    
    switch($action) {
        case 'create_usos':
            try {
                $customer_id = $_POST['customer_id'] ?? null;
                $order_date = $_POST['order_date'] ?? null;
                $total_quantity = $_POST['total_quantity'] ?? null;
                $monthly_usage = $_POST['monthly_usage'] ?? null;
                $production_lead_time = $_POST['production_lead_time'] ?? null;
                
                if (!$customer_id || !$order_date || !$total_quantity || !$monthly_usage || !$production_lead_time) {
                    echo json_encode(['success' => false, 'error' => 'All fields are required']);
                    exit;
                }
                
                $query = "INSERT INTO usos_config (customer_id, order_date, total_quantity_ordered, monthly_usage, production_lead_time_days) 
                          VALUES (:customer_id, :order_date, :total_quantity, :monthly_usage, :production_lead_time)";
                
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':customer_id', $customer_id);
                $stmt->bindParam(':order_date', $order_date);
                $stmt->bindParam(':total_quantity', $total_quantity);
                $stmt->bindParam(':monthly_usage', $monthly_usage);
                $stmt->bindParam(':production_lead_time', $production_lead_time);
                
                if ($stmt->execute()) {
                    $usos_id = $pdo->lastInsertId();
                    
                    // Create initial schedule entry
                    $arrival_date = date('Y-m-d', strtotime($order_date . ' + ' . $production_lead_time . ' days'));
                    $daily_usage = $monthly_usage / 30;
                    $days_until_runout = floor($total_quantity / $daily_usage);
                    $run_out_date = date('Y-m-d', strtotime($arrival_date . ' + ' . $days_until_runout . ' days'));
                    
                    $scheduleQuery = "INSERT INTO usos_schedule (usos_id, order_date, arrival_date, run_out_date) 
                                     VALUES (:usos_id, :order_date, :arrival_date, :run_out_date)";
                    
                    $scheduleStmt = $pdo->prepare($scheduleQuery);
                    $scheduleStmt->bindParam(':usos_id', $usos_id);
                    $scheduleStmt->bindParam(':order_date', $order_date);
                    $scheduleStmt->bindParam(':arrival_date', $arrival_date);
                    $scheduleStmt->bindParam(':run_out_date', $run_out_date);
                    $scheduleStmt->execute();
                    
                    echo json_encode(['success' => true, 'message' => 'USOS configuration created successfully', 'usos_id' => $usos_id]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to create USOS configuration']);
                }
            } catch(PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
            
        case 'update_usos':
            try {
                $usos_id = $_POST['usos_id'] ?? null;
                $customer_id = $_POST['customer_id'] ?? null;
                $order_date = $_POST['order_date'] ?? null;
                $total_quantity = $_POST['total_quantity'] ?? null;
                $monthly_usage = $_POST['monthly_usage'] ?? null;
                $production_lead_time = $_POST['production_lead_time'] ?? null;
                
                if (!$usos_id || !$customer_id || !$order_date || !$total_quantity || !$monthly_usage || !$production_lead_time) {
                    echo json_encode(['success' => false, 'error' => 'All fields are required']);
                    exit;
                }
                
                $query = "UPDATE usos_config SET 
                         customer_id = :customer_id,
                         order_date = :order_date,
                         total_quantity_ordered = :total_quantity,
                         monthly_usage = :monthly_usage,
                         production_lead_time_days = :production_lead_time
                         WHERE usos_id = :usos_id";
                
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':usos_id', $usos_id);
                $stmt->bindParam(':customer_id', $customer_id);
                $stmt->bindParam(':order_date', $order_date);
                $stmt->bindParam(':total_quantity', $total_quantity);
                $stmt->bindParam(':monthly_usage', $monthly_usage);
                $stmt->bindParam(':production_lead_time', $production_lead_time);
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'USOS configuration updated successfully']);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to update USOS configuration']);
                }
            } catch(PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
            
        case 'delete_usos':
            try {
                $usos_id = $_POST['usos_id'] ?? null;
                
                if (!$usos_id) {
                    echo json_encode(['success' => false, 'error' => 'USOS ID is required']);
                    exit;
                }
                
                // Soft delete
                $query = "UPDATE usos_config SET deleted_at = CURRENT_TIMESTAMP WHERE usos_id = :usos_id";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':usos_id', $usos_id);
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'USOS configuration deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to delete USOS configuration']);
                }
            } catch(PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
            
        case 'update_actual_arrival':
            try {
                $schedule_id = $_POST['schedule_id'] ?? null;
                $actual_arrival_date = $_POST['actual_arrival_date'] ?? null;
                $usos_id = $_POST['usos_id'] ?? null;
                
                if (!$schedule_id || !$actual_arrival_date || !$usos_id) {
                    echo json_encode(['success' => false, 'error' => 'Schedule ID, actual arrival date, and USOS ID are required']);
                    exit;
                }
                
                // Update the actual arrival date
                $updateQuery = "UPDATE usos_schedule SET actual_arrival_date = :actual_arrival_date, is_completed = 1 
                               WHERE schedule_id = :schedule_id";
                $updateStmt = $pdo->prepare($updateQuery);
                $updateStmt->bindParam(':actual_arrival_date', $actual_arrival_date);
                $updateStmt->bindParam(':schedule_id', $schedule_id);
                $updateStmt->execute();
                
                // Get USOS config for calculations
                $configQuery = "SELECT * FROM usos_config WHERE usos_id = :usos_id";
                $configStmt = $pdo->prepare($configQuery);
                $configStmt->bindParam(':usos_id', $usos_id);
                $configStmt->execute();
                $config = $configStmt->fetch(PDO::FETCH_ASSOC);
                
                if ($config) {
                    // Calculate next order date (should be ordered before stock runs out)
                    $daily_usage = $config['monthly_usage'] / 30;
                    $days_until_runout = floor($config['total_quantity_ordered'] / $daily_usage);
                    $run_out_date = date('Y-m-d', strtotime($actual_arrival_date . ' + ' . $days_until_runout . ' days'));
                    
                    // Next order should be placed: run_out_date - production_lead_time
                    $next_order_date = date('Y-m-d', strtotime($run_out_date . ' - ' . $config['production_lead_time_days'] . ' days'));
                    $next_arrival_date = date('Y-m-d', strtotime($next_order_date . ' + ' . $config['production_lead_time_days'] . ' days'));
                    
                    // Create next schedule entry
                    $insertQuery = "INSERT INTO usos_schedule (usos_id, order_date, arrival_date, run_out_date) 
                                   VALUES (:usos_id, :order_date, :arrival_date, :run_out_date)";
                    
                    $insertStmt = $pdo->prepare($insertQuery);
                    $insertStmt->bindParam(':usos_id', $usos_id);
                    $insertStmt->bindParam(':order_date', $next_order_date);
                    $insertStmt->bindParam(':arrival_date', $next_arrival_date);
                    $insertStmt->bindParam(':run_out_date', $run_out_date);
                    $insertStmt->execute();
                    
                    echo json_encode(['success' => true, 'message' => 'Actual arrival updated and next schedule created']);
                } else {
                    echo json_encode(['success' => false, 'error' => 'USOS configuration not found']);
                }
            } catch(PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
            
        case 'get_customers':
            try {
                $query = "SELECT customer_id, customer_name, customer_company_name FROM customer WHERE deleted_at IS NULL ORDER BY customer_name ASC";
                $stmt = $pdo->query($query);
                $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode(['success' => true, 'customers' => $customers]);
            } catch(PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
            
        case 'get_schedule':
            try {
                $usos_id = $_GET['usos_id'] ?? null;
                
                if (!$usos_id) {
                    echo json_encode(['success' => false, 'error' => 'USOS ID is required']);
                    exit;
                }
                
                $schedule = getUsosSchedule($usos_id);
                echo json_encode(['success' => true, 'schedule' => $schedule]);
            } catch(PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
            break;
    }
    
    exit;
}
?>
