<?php
require_once __DIR__ . '/../../global/main_configuration.php';
use Dompdf\Dompdf;

$pdo = openDB();

// Validate invoice ID
$invoiceId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($invoiceId <= 0) {
    die("Invalid invoice ID.");
}

// Fetch invoice data
$stmt = $pdo->prepare("
SELECT 
    i.invoice_number,
    c.customer_name,
    i.total_amount,
    ii.product_name,
    ii.quantity,
    ii.unit_price,
    ii.total_price,
    p.image_url
FROM invoice i
JOIN customer c ON i.customer_id = c.customer_id
JOIN invoice_item ii ON i.invoice_id = ii.invoice_id
JOIN product p ON ii.product_id = p.product_id
WHERE i.invoice_id = ?
");
$stmt->execute([$invoiceId]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    die("Invoice not found.");
}

// Get general invoice info from first row
$invoice = $rows[0];

// Start building HTML
$html = "
<h1>Invoice " . htmlspecialchars($invoice['invoice_number']) . "</h1>
<p>Customer: " . htmlspecialchars($invoice['customer_name']) . "</p>
<table border='1' cellpadding='5' cellspacing='0'>
<tr>
    <th>No.</th>
    <th>Item</th>
    <th>Image</th>
    <th>Qty</th>
    <th>Unit Price (RM)</th>
    <th>Total Price (RM)</th>
</tr>
";

// Loop through items
$no = 1;
foreach ($rows as $item) {
    $images = explode(',', $item['image_url']);
    $firstImage = trim($images[0]);

    $imagePath = __DIR__ . '/../../media/' . $firstImage;

    // Base64 embed image
    if (file_exists($imagePath)) {
        $imageBase64 = base64_encode(file_get_contents($imagePath));
        $imageMime = mime_content_type($imagePath);
        $imgSrc = 'data:' . $imageMime . ';base64,' . $imageBase64;
    } else {
        // Optional: use a placeholder if image not found
        $imgSrc = ''; // or 'data:image/png;base64,...' for default image
    }

    $html .= "
    <tr>
        <td>{$no}</td>
        <td>" . htmlspecialchars($item['product_name']) . "</td>
        <td><img src='{$imgSrc}' width='145' height='126'></td>
        <td>{$item['quantity']}</td>
        <td>" . number_format($item['unit_price'], 2) . "</td>
        <td>" . number_format($item['total_price'], 2) . "</td>
    </tr>
    ";
    $no++;
}

$html .= "
</table>
<p><strong>Total: RM " . number_format($invoice['total_amount'], 2) . "</strong></p>
";

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output PDF
ob_clean(); // Prevent corrupted PDF
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="invoice_' . $invoice['invoice_number'] . '.pdf"');
echo $dompdf->output();
