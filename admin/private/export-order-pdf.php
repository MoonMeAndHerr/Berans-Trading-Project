<?php
require_once __DIR__ . '/../../global/main_configuration.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$pdo = openDB();

// Validate invoice ID
$invoiceId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($invoiceId <= 0) {
    die("Invalid invoice ID.");
}

$stmt = $pdo->prepare("
SELECT 
    i.invoice_id,
    i.invoice_number,
    i.created_at,
    i.status,
    i.subtotal,
    i.discount_amount,
    i.discount_type,
    i.discount_value,
    i.grand_total,
    i.total_amount,
    c.customer_name,
    c.customer_phone,
    c.customer_address,
    c.customer_city,
    c.customer_region,
    c.customer_postcode,
    c.customer_country,
    c.customer_company_name,
    c.customer_designation,
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
ORDER BY ii.invoice_item_id
");
$stmt->execute([$invoiceId]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    die("Invoice not found.");
}


$invoice = $rows[0];
$invoiceDate = date('d F Y', strtotime($invoice['created_at']));
$invoiceTime = date('h:i A', strtotime($invoice['created_at']));


$customerAddress = $invoice['customer_address'];
if ($invoice['customer_city']) $customerAddress .= ', ' . $invoice['customer_city'];
if ($invoice['customer_postcode']) $customerAddress .= ' ' . $invoice['customer_postcode'];
if ($invoice['customer_region']) $customerAddress .= ', ' . $invoice['customer_region'];
if ($invoice['customer_country']) $customerAddress .= ', ' . $invoice['customer_country'];


$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            color: #2c3e50;
            background: white;
            padding: 40px 50px;
        }
        
        /* Header */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 40px;
        }
        .header-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .header-right {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: top;
        }
        .company-name {
            font-size: 36pt;
            font-weight: 300;
            color: #06b6d4;
            letter-spacing: -1px;
            line-height: 1;
        }
        .company-tagline {
            font-size: 10pt;
            color: #94a3b8;
            margin-top: 8px;
            font-weight: 300;
        }
        .invoice-title {
            font-size: 48pt;
            font-weight: 200;
            color: #e2e8f0;
            letter-spacing: -2px;
            line-height: 1;
        }
        .invoice-number {
            font-size: 16pt;
            color: #06b6d4;
            margin-top: 8px;
            font-weight: 600;
        }
        
        /* Divider */
        .divider {
            height: 1px;
            background: linear-gradient(to right, #06b6d4, transparent);
            margin: 30px 0;
        }
        
        /* Info Section */
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 40px;
        }
        .info-col {
            display: table-cell;
            width: 33.33%;
            vertical-align: top;
            padding-right: 30px;
        }
        .info-title {
            font-size: 9pt;
            font-weight: 700;
            color: #06b6d4;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 15px;
        }
        .info-content {
            font-size: 10pt;
            line-height: 1.8;
            color: #475569;
        }
        .info-content strong {
            display: block;
            font-size: 12pt;
            color: #1e293b;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .info-row {
            margin: 8px 0;
        }
        .info-label {
            display: inline-block;
            width: 70px;
            color: #94a3b8;
            font-size: 9pt;
        }
        .info-value {
            color: #1e293b;
            font-weight: 500;
        }
        
        /* Status Badge */
        .status {
            display: inline-block;
            padding: 3px 10px;
            font-size: 8pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 2px;
        }
        .status-completed {
            background: #10b981;
            color: white;
        }
        .status-pending {
            background: #f59e0b;
            color: white;
        }
        
        /* Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .items-table thead th {
            background: white;
            border-bottom: 2px solid #06b6d4;
            padding: 12px 10px;
            text-align: left;
            font-size: 8pt;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .items-table tbody td {
            padding: 15px 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 10pt;
            color: #475569;
            vertical-align: middle;
        }
        .items-table tbody tr:last-child td {
            border-bottom: 2px solid #06b6d4;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        .product-name {
            color: #1e293b;
            font-weight: 500;
        }
        
        /* Summary */
        .summary {
            margin-top: 40px;
            text-align: right;
        }
        .summary-table {
            display: inline-block;
            min-width: 350px;
        }
        .summary-row {
            display: table;
            width: 100%;
            padding: 10px 0;
        }
        .summary-row .label {
            display: table-cell;
            text-align: left;
            font-size: 10pt;
            color: #64748b;
            padding-right: 30px;
        }
        .summary-row .value {
            display: table-cell;
            text-align: right;
            font-size: 11pt;
            color: #1e293b;
            font-weight: 600;
        }
        .summary-total {
            background: #06b6d4;
            color: white;
            padding: 20px;
            margin-top: 15px;
        }
        .summary-total .label,
        .summary-total .value {
            color: white;
            font-size: 14pt;
            font-weight: 700;
        }
        .discount-value {
            color: #ef4444;
        }
        
        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px 50px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 9pt;
            color: #94a3b8;
            background: white;
        }
        .footer-brand {
            color: #06b6d4;
            font-weight: 600;
            font-size: 10pt;
            margin-bottom: 5px;
        }
        
        /* Page Break */
        .page-break {
            page-break-after: always;
        }
        
        /* Page Container */
        .page-content {
            min-height: 900px;
            padding-bottom: 80px;
        }
    </style>
</head>
<body>';


$totalItems = count($rows);
$firstPageItems = 4;
$otherPageItems = 8;


if ($totalItems <= $firstPageItems) {
    $totalPages = 1;
} else {
    $remainingItems = $totalItems - $firstPageItems;
    $totalPages = 1 + ceil($remainingItems / $otherPageItems);
}

for ($page = 0; $page < $totalPages; $page++) {
    if ($page == 0) {
  
        $startIndex = 0;
        $itemsOnPage = array_slice($rows, 0, $firstPageItems);
    } else {

        $startIndex = $firstPageItems + (($page - 1) * $otherPageItems);
        $itemsOnPage = array_slice($rows, $startIndex, $otherPageItems);
    }
    
    $html .= '<div class="page-content">';
    

    if ($page == 0) {
        $html .= '
    
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <div class="company-name">BERANS</div>
            <div class="company-tagline">Premium Trading Solutions</div>
        </div>
        <div class="header-right">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">#' . htmlspecialchars($invoice['invoice_number']) . '</div>
        </div>
    </div>
    
    <div class="divider"></div>
    
    <!-- Info Grid -->
    <div class="info-grid">
        <div class="info-col">
            <div class="info-title">Bill To</div>
            <div class="info-content">
                <strong>' . htmlspecialchars($invoice['customer_name']) . '</strong>';
                    
if ($invoice['customer_company_name']) {
    $html .= '<div style="font-size:10pt;color:#64748b;margin-top:3px;">' . htmlspecialchars($invoice['customer_company_name']);
    if ($invoice['customer_designation']) {
        $html .= ' · ' . htmlspecialchars($invoice['customer_designation']);
    }
    $html .= '</div>';
}

$html .= '
                <div style="margin-top:10px;line-height:1.6;">' . nl2br(htmlspecialchars($customerAddress)) . '</div>';

if ($invoice['customer_phone']) {
    $html .= '<div style="margin-top:8px;color:#06b6d4;font-weight:500;">' . htmlspecialchars($invoice['customer_phone']) . '</div>';
}

$html .= '
            </div>
        </div>
        <div class="info-col">
            <div class="info-title">Invoice Details</div>
            <div class="info-content">
                <div class="info-row">
                    <span class="info-label">Date</span>
                    <span class="info-value">' . $invoiceDate . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Time</span>
                    <span class="info-value">' . $invoiceTime . '</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="status status-' . strtolower($invoice['status']) . '">' . 
                        strtoupper($invoice['status']) . 
                    '</span>
                </div>
            </div>
        </div>
        <div class="info-col">
            <div class="info-title">Payment Summary</div>
            <div class="info-content">
                <div class="info-row">
                    <span class="info-label">Subtotal</span>
                    <span class="info-value">RM ' . number_format($invoice['subtotal'], 2) . '</span>
                </div>';

if ($invoice['discount_amount'] > 0) {
    $html .= '
                <div class="info-row">
                    <span class="info-label">Discount</span>
                    <span class="info-value" style="color:#ef4444;">-RM ' . number_format($invoice['discount_amount'], 2) . '</span>
                </div>';
}

$html .= '
                <div class="info-row" style="margin-top:8px;padding-top:8px;border-top:1px solid #e2e8f0;">
                    <span class="info-label" style="font-weight:700;color:#06b6d4;">Total</span>
                    <span class="info-value" style="font-size:14pt;color:#06b6d4;font-weight:700;">RM ' . number_format($invoice['total_amount'], 2) . '</span>
                </div>
            </div>
        </div>
    </div>';
    } else {

        $html .= '
    <div style="margin-bottom:30px;">
        <div style="font-size:10pt;color:#94a3b8;">Invoice <span style="color:#06b6d4;font-weight:600;">#' . htmlspecialchars($invoice['invoice_number']) . '</span> - Page ' . ($page + 1) . '</div>
        <div class="divider" style="margin:15px 0;"></div>
    </div>';
    }
    
    $html .= '
    
    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width:5%;">NO</th>
                <th style="width:12%;" class="text-center">IMAGE</th>
                <th style="width:40%;">PRODUCT</th>
                <th style="width:10%;" class="text-center">QTY</th>
                <th style="width:15%;" class="text-right">UNIT PRICE</th>
                <th style="width:18%;" class="text-right">TOTAL</th>
            </tr>
        </thead>
        <tbody>';

$itemNo = $startIndex + 1;
foreach ($itemsOnPage as $item) {
    $images = explode(',', $item['image_url']);
    $firstImage = trim($images[0]);
    $imagePath = __DIR__ . '/../../media/' . $firstImage;

    if (file_exists($imagePath) && is_readable($imagePath)) {
        $imageMime = null;
    
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $imageMime = finfo_file($finfo, $imagePath);
            finfo_close($finfo);
        }
    
        if (!$imageMime) {
            $ext = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp'
            ];
            $imageMime = $mimeTypes[$ext] ?? 'application/octet-stream';
        }
    
        $imageBase64 = base64_encode(file_get_contents($imagePath));
        $imgSrc = 'data:' . $imageMime . ';base64,' . $imageBase64;
        $imageTag = '<img src="' . $imgSrc . '" class="product-image">';
    } else {
        $imageTag = '<div style="width:60px;height:60px;background:#f1f5f9;border-radius:4px;"></div>';
    }


    $html .= '
            <tr>
                <td style="color:#94a3b8;font-weight:600;">' . $itemNo . '</td>
                <td class="text-center">' . $imageTag . '</td>
                <td><span class="product-name">' . htmlspecialchars($item['product_name']) . '</span></td>
                <td class="text-center" style="font-weight:600;">' . number_format($item['quantity']) . '</td>
                <td class="text-right">RM ' . number_format($item['unit_price'], 2) . '</td>
                <td class="text-right" style="font-weight:600;color:#1e293b;">RM ' . number_format($item['total_price'], 2) . '</td>
            </tr>';
    $itemNo++;
}

$html .= '
        </tbody>
    </table>
    
    </div><!-- End page-content -->';
    

    if ($page < $totalPages - 1) {
        $html .= '<div class="page-break"></div>';
    }
}

$html .= '
    
    <!-- Footer -->
    <div class="footer">
        <div class="footer-brand">BERANS TRADING</div>
        <div>Thank you for your business</div>
    </div>
</body>
</html>
';

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'Helvetica');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();


ob_clean();
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="BERANS_TRADING_Invoice_' . $invoice['invoice_number'] . '.pdf"');
echo $dompdf->output();
