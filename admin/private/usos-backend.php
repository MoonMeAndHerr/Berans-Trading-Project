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
            c.customer_company_name,
            ps.shipping_name,
            ps.delivery_days
        FROM usos_config u
        LEFT JOIN customer c ON u.customer_id = c.customer_id
        LEFT JOIN price_shipping ps ON u.shipping_code = ps.shipping_code
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
        ORDER BY order_date DESC
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
                $shipping_code = $_POST['shipping_code'] ?? null;
                
                if (!$customer_id || !$order_date || !$total_quantity || !$monthly_usage || !$production_lead_time || !$shipping_code) {
                    echo json_encode(['success' => false, 'error' => 'All fields are required']);
                    exit;
                }
                
                // Get delivery days from shipping method
                $stmt_shipping = $pdo->prepare("SELECT delivery_days FROM price_shipping WHERE shipping_code = ?");
                $stmt_shipping->execute([$shipping_code]);
                $shipping_data = $stmt_shipping->fetch(PDO::FETCH_ASSOC);
                $delivery_days = $shipping_data['delivery_days'] ?? 0;
                
                $query = "INSERT INTO usos_config (customer_id, order_date, total_quantity_ordered, monthly_usage, production_lead_time_days, shipping_code) 
                          VALUES (:customer_id, :order_date, :total_quantity, :monthly_usage, :production_lead_time, :shipping_code)";
                
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':customer_id', $customer_id);
                $stmt->bindParam(':order_date', $order_date);
                $stmt->bindParam(':total_quantity', $total_quantity);
                $stmt->bindParam(':monthly_usage', $monthly_usage);
                $stmt->bindParam(':production_lead_time', $production_lead_time);
                $stmt->bindParam(':shipping_code', $shipping_code);
                
                if ($stmt->execute()) {
                    $usos_id = $pdo->lastInsertId();
                    
                    // Save items if provided
                    $items = json_decode($_POST['items'] ?? '[]', true);
                    if (!empty($items)) {
                        $insertQuery = "INSERT INTO usos_items (usos_id, product_id, price_id, quantity, unit_price) 
                                       VALUES (:usos_id, :product_id, :price_id, :quantity, :unit_price)";
                        $insertStmt = $pdo->prepare($insertQuery);
                        
                        foreach ($items as $item) {
                            $insertStmt->execute([
                                ':usos_id' => $usos_id,
                                ':product_id' => $item['product_id'],
                                ':price_id' => $item['price_id'] ?? null,
                                ':quantity' => $item['quantity'],
                                ':unit_price' => $item['unit_price']
                            ]);
                        }
                    }
                    
                    // Create initial schedule entry using production_lead_time + delivery_days
                    $total_lead_time = $production_lead_time + $delivery_days;
                    $arrival_date = date('Y-m-d', strtotime($order_date . ' + ' . $total_lead_time . ' days'));
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
                $total_quantity = $_POST['total_quantity_ordered'] ?? null;
                $monthly_usage = $_POST['monthly_usage'] ?? null;
                $production_lead_time = $_POST['production_lead_time_days'] ?? null;
                $shipping_code = $_POST['shipping_code'] ?? null;
                
                if (!$usos_id || !$total_quantity || !$monthly_usage || !$production_lead_time || !$shipping_code) {
                    echo json_encode(['success' => false, 'error' => 'All fields are required']);
                    exit;
                }
                
                // Only update the editable fields (not customer or order date)
                $query = "UPDATE usos_config SET 
                         total_quantity_ordered = :total_quantity,
                         monthly_usage = :monthly_usage,
                         production_lead_time_days = :production_lead_time,
                         shipping_code = :shipping_code,
                         updated_at = NOW()
                         WHERE usos_id = :usos_id";
                
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':usos_id', $usos_id);
                $stmt->bindParam(':total_quantity', $total_quantity);
                $stmt->bindParam(':monthly_usage', $monthly_usage);
                $stmt->bindParam(':production_lead_time', $production_lead_time);
                $stmt->bindParam(':shipping_code', $shipping_code);
                
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
                    // Get delivery days from shipping method
                    $stmt_shipping = $pdo->prepare("SELECT delivery_days FROM price_shipping WHERE shipping_code = ?");
                    $stmt_shipping->execute([$config['shipping_code']]);
                    $shipping_data = $stmt_shipping->fetch(PDO::FETCH_ASSOC);
                    $delivery_days = $shipping_data['delivery_days'] ?? 0;
                    
                    // Calculate when current stock will run out
                    $daily_usage = $config['monthly_usage'] / 30;
                    $days_until_runout = floor($config['total_quantity_ordered'] / $daily_usage);
                    $current_run_out_date = date('Y-m-d', strtotime($actual_arrival_date . ' + ' . $days_until_runout . ' days'));
                    
                    // Next arrival should be exactly when current stock runs out (no gap in supply)
                    $next_arrival_date = $current_run_out_date;
                    
                    // Next order date: arrival date - (production_lead_time + delivery_days)
                    $total_lead_time = $config['production_lead_time_days'] + $delivery_days;
                    $next_order_date = date('Y-m-d', strtotime($next_arrival_date . ' - ' . $total_lead_time . ' days'));
                    
                    // Calculate when the NEXT stock will run out (after next arrival)
                    $next_run_out_date = date('Y-m-d', strtotime($next_arrival_date . ' + ' . $days_until_runout . ' days'));
                    
                    // Create next schedule entry
                    $insertQuery = "INSERT INTO usos_schedule (usos_id, order_date, arrival_date, run_out_date) 
                                   VALUES (:usos_id, :order_date, :arrival_date, :run_out_date)";
                    
                    $insertStmt = $pdo->prepare($insertQuery);
                    $insertStmt->bindParam(':usos_id', $usos_id);
                    $insertStmt->bindParam(':order_date', $next_order_date);
                    $insertStmt->bindParam(':arrival_date', $next_arrival_date);
                    $insertStmt->bindParam(':run_out_date', $next_run_out_date);
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
            
        case 'get_usos':
            try {
                $usos_id = $_GET['usos_id'] ?? null;
                
                if (!$usos_id) {
                    echo json_encode(['success' => false, 'error' => 'USOS ID is required']);
                    exit;
                }
                
                // Get USOS configuration with shipping info
                $query = "
                    SELECT 
                        u.*,
                        c.customer_name,
                        c.customer_company_name,
                        ps.shipping_name,
                        ps.delivery_days
                    FROM usos_config u
                    LEFT JOIN customer c ON u.customer_id = c.customer_id
                    LEFT JOIN price_shipping ps ON u.shipping_code = ps.shipping_code
                    WHERE u.usos_id = :usos_id AND u.deleted_at IS NULL
                ";
                
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':usos_id', $usos_id);
                $stmt->execute();
                $config = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$config) {
                    echo json_encode(['success' => false, 'error' => 'USOS configuration not found']);
                    exit;
                }
                
                // Get schedule
                $schedule = getUsosSchedule($usos_id);
                
                echo json_encode(['success' => true, 'data' => $config, 'schedule' => $schedule]);
            } catch(PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
            
        case 'update_schedule_status':
            try {
                $schedule_id = $_POST['schedule_id'] ?? null;
                $is_completed = $_POST['is_completed'] ?? null;
                
                if ($schedule_id === null || $is_completed === null) {
                    echo json_encode(['success' => false, 'error' => 'Schedule ID and status are required']);
                    exit;
                }
                
                $query = "UPDATE usos_schedule SET is_completed = :is_completed WHERE schedule_id = :schedule_id";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':schedule_id', $schedule_id);
                $stmt->bindParam(':is_completed', $is_completed);
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Schedule status updated successfully']);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to update schedule status']);
                }
            } catch(PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
            
        case 'get_shipping_methods':
            try {
                $query = "SELECT shipping_code, shipping_name, delivery_days FROM price_shipping ORDER BY shipping_name";
                $stmt = $pdo->query($query);
                $shipping_methods = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode(['success' => true, 'shipping_methods' => $shipping_methods]);
            } catch(PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
            
        case 'get_product_hierarchy':
            try {
                $sections = $pdo->query("SELECT section_id, section_name FROM section ORDER BY section_name")->fetchAll(PDO::FETCH_ASSOC);
                $categories = $pdo->query("SELECT category_id, category_name, section_id FROM category ORDER BY category_name")->fetchAll(PDO::FETCH_ASSOC);
                $subcategories = $pdo->query("SELECT subcategory_id, subcategory_name, category_id FROM subcategory ORDER BY subcategory_name")->fetchAll(PDO::FETCH_ASSOC);
                
                $products = $pdo->query("
                    SELECT 
                        p.product_id,
                        p.section_id,
                        p.category_id,
                        p.subcategory_id,
                        CONCAT(
                            p.product_code, ' | ',
                            IFNULL(m.material_name, ''), ' ',
                            IFNULL(pt.product_name, ''), ' ',
                            p.size_1, '*', p.size_2, '*', p.size_3, ' ',
                            IFNULL(p.variant,'')
                        ) AS display_name,
                        pr.price_id,
                        pr.new_moq_quantity as moq,
                        COALESCE(pr.new_selling_price, 0) as price
                    FROM product p
                    LEFT JOIN material m ON p.material_id = m.material_id
                    LEFT JOIN product_type pt ON pt.product_type_id = p.product_type_id
                    LEFT JOIN (
                        SELECT p1.* 
                        FROM price p1
                        INNER JOIN (
                            SELECT product_id, MAX(price_id) as max_price_id
                            FROM price
                            GROUP BY product_id
                        ) p2 ON p1.price_id = p2.max_price_id
                    ) pr ON p.product_id = pr.product_id
                    WHERE p.deleted_at IS NULL
                ")->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode([
                    'success' => true,
                    'sections' => $sections,
                    'categories' => $categories,
                    'subcategories' => $subcategories,
                    'products' => $products
                ]);
            } catch(PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
            
        case 'get_usos_items':
            try {
                $usos_id = $_GET['usos_id'] ?? null;
                if (!$usos_id) {
                    echo json_encode(['success' => false, 'error' => 'USOS ID required']);
                    exit;
                }
                
                $query = "
                    SELECT 
                        ui.*,
                        CONCAT(
                            p.product_code, ' | ',
                            IFNULL(m.material_name, ''), ' ',
                            IFNULL(pt.product_name, ''), ' ',
                            p.size_1, '*', p.size_2, '*', p.size_3, ' ',
                            IFNULL(p.variant,'')
                        ) AS product_name
                    FROM usos_items ui
                    LEFT JOIN product p ON ui.product_id = p.product_id
                    LEFT JOIN material m ON p.material_id = m.material_id
                    LEFT JOIN product_type pt ON pt.product_type_id = p.product_type_id
                    WHERE ui.usos_id = :usos_id AND ui.deleted_at IS NULL
                    ORDER BY ui.created_at ASC
                ";
                
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':usos_id', $usos_id);
                $stmt->execute();
                $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode(['success' => true, 'items' => $items]);
            } catch(PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            break;
            
        case 'save_usos_items':
            try {
                $usos_id = $_POST['usos_id'] ?? null;
                $items = json_decode($_POST['items'] ?? '[]', true);
                
                if (!$usos_id || empty($items)) {
                    echo json_encode(['success' => false, 'error' => 'USOS ID and items are required']);
                    exit;
                }
                
                // Begin transaction
                $pdo->beginTransaction();
                
                // Soft delete existing items
                $deleteQuery = "UPDATE usos_items SET deleted_at = NOW() WHERE usos_id = :usos_id";
                $deleteStmt = $pdo->prepare($deleteQuery);
                $deleteStmt->bindParam(':usos_id', $usos_id);
                $deleteStmt->execute();
                
                // Insert new items
                $insertQuery = "INSERT INTO usos_items (usos_id, product_id, price_id, quantity, unit_price) 
                               VALUES (:usos_id, :product_id, :price_id, :quantity, :unit_price)";
                $insertStmt = $pdo->prepare($insertQuery);
                
                foreach ($items as $item) {
                    $insertStmt->execute([
                        ':usos_id' => $usos_id,
                        ':product_id' => $item['product_id'],
                        ':price_id' => $item['price_id'] ?? null,
                        ':quantity' => $item['quantity'],
                        ':unit_price' => $item['unit_price']
                    ]);
                }
                
                $pdo->commit();
                echo json_encode(['success' => true, 'message' => 'Items saved successfully']);
            } catch(PDOException $e) {
                $pdo->rollBack();
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
