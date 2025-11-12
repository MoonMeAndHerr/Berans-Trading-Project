<?php
require_once __DIR__ . '/../../global/main_configuration.php';
use League\OAuth2\Client\Provider\GenericProvider;
use GuzzleHttp\Client;

function refreshXeroToken() {

    // 2️⃣ Get stored refresh token from DB
    $pdo = openDB();
    $stmt = $pdo->query("SELECT xero_refresh_token, xero_tenant_id FROM site_config WHERE company_id = 1 LIMIT 1");
    $row  = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || empty($row['xero_refresh_token'])) {
        throw new Exception("❌ No refresh token saved!");
    }

    $refreshToken = $row['xero_refresh_token'];

    // 3️⃣ Create OAuth provider
    $provider = new GenericProvider([
        'clientId'                => '401AA121B0D7485193B11FA9CCA0546B',
        'clientSecret'            => 'Frpko_qPMO1nY2kMbHsnW5dqLssheS_oeNFrxvxh6k7vIpTu',
        'redirectUri'             => 'http://localhost/Berans-Trading-Project/admin/public/callback.php',
        'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
        'urlAccessToken'          => 'https://identity.xero.com/connect/token',
        'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
    ]);

    // 4️⃣ Request new access token using refresh token
    $newToken = $provider->getAccessToken('refresh_token', [
        'refresh_token' => $refreshToken
    ]);

    // 5️⃣ Save new tokens back to DB (important!)
    $stmt = $pdo->prepare("
        UPDATE site_config SET 
            xero_refresh_token = :refresh,
            xero_ttl = :ttl
        WHERE company_id = 1
        LIMIT 1
    ");

    $stmt->execute([
        ':refresh' => $newToken->getRefreshToken(),
        ':ttl'     => date('Y-m-d H:i:s', $newToken->getExpires())
    ]);

    // Return fresh access token + tenant_id
    return [
        'access_token' => $newToken->getToken(),
        'tenant_id'    => $row['xero_tenant_id']
    ];
}
