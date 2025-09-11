<?php
use League\OAuth2\Client\Provider\GenericProvider;
use GuzzleHttp\Client;
// ✅ Session and configuration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../global/main_configuration.php';
require_once __DIR__ . '/../private/auth_check.php';

// ✅ Initialize supplier_id (from GET)
$supplier_id = $_GET['supplier_id'] ?? null;

// ✅ Open connection
$pdo = openDB();

$errors  = [];
$success_add = '';
$success_update = '';
$success_delete = '';

// ✅ Prepare variables for form data (for update form)
$supplier_name  = '';
$contact_person = '';
$phone          = '';
$email          = '';
$address        = '';
$city = '';
$region          = '';
$postcode          = '';
$country        = '';
$notes          = '';

// ✅ Handle delete form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_supplier_id'])) {
    $delete_id = (int)$_POST['delete_supplier_id'];

    try {
        // Optional: check if supplier exists
        $check = $pdo->prepare("SELECT supplier_id FROM supplier WHERE supplier_id = ?");
        $check->execute([$delete_id]);
        if ($check->rowCount() > 0) {
            // Perform delete
            $stmt = $pdo->prepare("DELETE FROM supplier WHERE supplier_id = ?");
            $stmt->execute([$delete_id]);

            // Redirect with success for delete
            $_SESSION['success_delete'] = "🗑️ Supplier deleted successfully!";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $errors[] = "Supplier not found, cannot delete.";
        }
    } catch (PDOException $e) {
        $errors[] = "Database error during deletion: " . $e->getMessage();
    }
}

// ✅ Handle form submission (Insert or Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_supplier_id'])) {
    // Check if update or add form based on supplier_id in POST
    $is_update = !empty($_POST['supplier_id']);

    // Collect and sanitize input
    $supplier_name   = trim($_POST['supplier_name'] ?? '');
    $contact_person  = trim($_POST['supplier_contact_person'] ?? '');
    $phone           = trim($_POST['phone'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $address         = trim($_POST['address'] ?? '');
    $city           = trim($_POST['city'] ?? '');
    $region           = trim($_POST['region'] ?? '');
    $postcode         = trim($_POST['postcode'] ?? '');
    $country           = trim($_POST['country'] ?? '');
    $notes           = trim($_POST['notes'] ?? '');


    // Validation
    if (empty($supplier_name)) {
        $errors[] = "Supplier name is required.";
    }
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($errors)) {
        try {
            if ($is_update) {
                $supplier_id = $_POST['supplier_id'];
                $xero_relation = $_POST['xero_relation'] ?? null;

                try {

                $xeroAuth = refreshXeroToken(); // always returns valid token
                $accessToken = $xeroAuth['access_token'];
                $tenantId    = $xeroAuth['tenant_id'];
                
                $client = new Client();
                $response = $client->post("https://api.xero.com/api.xro/2.0/Contacts/$xero_relation", [
                    'headers' => [
                        'Authorization'   => 'Bearer ' . $accessToken,
                        'Accept'          => 'application/json',
                        'Content-Type'    => 'application/json',
                        'Xero-tenant-id'  => $tenantId,
                    ],
                    'json' => [
                        'ContactID' => $xero_relation,
                        'Name' => $supplier_name,
                        'FirstName' => $contact_person,
                        'EmailAddress' => $email,
                        'IsSupplier' => true,  
                        'Addresses' => [[
                            'AddressType' => 'STREET',
                            'AddressLine1' => $address,
                            'City' => $city,
                            'Region' => $region,
                            'PostalCode' => $postcode,
                            'Country' => $country
                        ]],
                        'Phones' => [[
                            'PhoneType' => 'DEFAULT',
                            'PhoneNumber' => $phone
                        ]],
                        'ContactPersons' => [[
                            'FirstName' => $contact_person,
                            'EmailAddress' => $email
                        ]]

                    ]
                ]);


            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $responseBody = $e->getResponse()->getBody()->getContents();
                $errors[] = 'Xero API error: ' . $responseBody;

            }
                // Update query
                $stmt = $pdo->prepare("
                    UPDATE supplier 
                    SET supplier_name=?, supplier_contact_person=?, phone=?, email=?, address=?, city=?, region=?, postcode=?, country=?, notes=? 
                    WHERE xero_relation=? AND supplier_id=?");
                $stmt->execute([
                    $supplier_name,
                    $contact_person,
                    $phone,
                    $email,
                    $address,
                    $city,
                    $region,
                    $postcode,
                    $country,
                    $notes,
                    $xero_relation,
                    $supplier_id
                ]);

                // Redirect with success for update
                $_SESSION['success_update'] = "✅ Supplier updated successfully!";
                header('Location: ' . $_SERVER['PHP_SELF'] . '?supplier_id=' . $supplier_id);
                exit();

            } else {

                $xeroAuth = refreshXeroToken(); // always returns valid token
                $accessToken = $xeroAuth['access_token'];
                $tenantId    = $xeroAuth['tenant_id'];
                
                $client = new Client();

                $response = $client->post('https://api.xero.com/api.xro/2.0/Contacts', [
                    'headers' => [
                        'Authorization'   => 'Bearer ' . $accessToken,
                        'Accept'          => 'application/json',
                        'Content-Type'    => 'application/json',
                        'Xero-tenant-id'  => $tenantId,
                    ],
                    'json' => [
                        'Name' => $supplier_name,
                        'FirstName' => $contact_person,
                        'EmailAddress' => $email,
                        'IsSupplier' => true,  
                        'Addresses' => [[
                            'AddressType' => 'STREET',
                            'AddressLine1' => $address,
                            'City' => $city,
                            'Region' => $region,
                            'PostalCode' => $postcode,
                            'Country' => $country
                        ]],
                        'Phones' => [[
                            'PhoneType' => 'DEFAULT',
                            'PhoneNumber' => $phone
                        ]],
                        'ContactPersons' => [[
                            'FirstName' => $contact_person,
                            'EmailAddress' => $email
                        ]]
                    ]
                ]);

                $result = json_decode($response->getBody(), true);

                if (!empty($result['Contacts'][0]['ContactID'])) {
                    $xero_relation = $result['Contacts'][0]['ContactID'];
                } else {
                    $errors[] = 'Failed to retrieve ContactID from Xero response.';
                }
                
                // Insert query
                $stmt = $pdo->prepare("
                    INSERT INTO supplier (supplier_name, supplier_contact_person, phone, email, address, city, region, postcode, country, notes, xero_relation) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $supplier_name,
                    $contact_person,
                    $phone,
                    $email,
                    $address,
                    $city,
                    $region,
                    $postcode,
                    $country,
                    $notes,
                    $xero_relation
                ]);

                // Redirect with success for add
                $_SESSION['success_add'] = "✅ Supplier added successfully!";
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

// ✅ Fetch success messages from session and clear them (so messages show once)
if (isset($_SESSION['success_add'])) {
    $success_add = $_SESSION['success_add'];
    unset($_SESSION['success_add']);
}
if (isset($_SESSION['success_update'])) {
    $success_update = $_SESSION['success_update'];
    unset($_SESSION['success_update']);
}
if (isset($_SESSION['success_delete'])) {
    $success_delete = $_SESSION['success_delete'];
    unset($_SESSION['success_delete']);
}

// ✅ If editing (GET with supplier_id), fetch supplier data for update form
if ($supplier_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM supplier WHERE supplier_id = ?");
        $stmt->execute([$supplier_id]);
        $supplier = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($supplier) {
            $supplier_name  = $supplier['supplier_name'];
            $contact_person = $supplier['supplier_contact_person'];
            $phone          = $supplier['phone'];
            $email          = $supplier['email'];
            $address        = $supplier['address'];
            $city          = $supplier['city'];
            $region          = $supplier['region'];
            $postcode        = $supplier['postcode'];
            $country          = $supplier['country'];
            $notes          = $supplier['notes'];
            $xero          = $supplier['xero_relation'];
        } else {
            $errors[] = "Supplier not found.";
            $supplier_id = null;
        }
    } catch (PDOException $e) {
        $errors[] = "Database error: " . $e->getMessage();
        $supplier_id = null;
    }
}

// ✅ Count staff
$staffCount = 0;
try {
    $stmt = $pdo->query("SELECT COUNT(*) AS total_staff FROM staff");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $staffCount = $row['total_staff'];
    }
} catch (PDOException $e) {
    $staffCount = 0;
}

// ✅ Fetch all suppliers for dropdown
$allSuppliers = [];
try {
    $stmt = $pdo->query("
        SELECT supplier_id, supplier_name, supplier_contact_person, phone, email, address, city, region, postcode, country, notes, xero_relation
        FROM supplier ORDER BY supplier_name ASC");
    $allSuppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $allSuppliers = [];
}

// ✅ Fetch last added staff date
$lastAddedDate = null;
try {
    $stmt = $pdo->query("SELECT created_at FROM staff ORDER BY created_at DESC LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $lastAddedDate = $row['created_at'];
    }
} catch (PDOException $e) {
    $lastAddedDate = null;
}

// ✅ Fetch supplier count and last created_at
$supplierCount   = 0;
$lastSupplierDate = null;
try {
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM supplier");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $supplierCount = $row['total'] ?? 0;

    $stmt2 = $pdo->query("SELECT created_at FROM supplier ORDER BY created_at DESC LIMIT 1");
    $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $lastSupplierDate = $row2['created_at'] ?? null;
} catch (PDOException $e) {
    // silently ignore
}

// ✅ Close DB
closeDB($pdo);
?>
