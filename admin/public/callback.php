<?php
require_once __DIR__ . '/../../global/main_configuration.php';

use League\OAuth2\Client\Provider\GenericProvider;

session_start();

$provider = new GenericProvider([
    'clientId'                => '401AA121B0D7485193B11FA9CCA0546B',
    'clientSecret'            => 'Frpko_qPMO1nY2kMbHsnW5dqLssheS_oeNFrxvxh6k7vIpTu',
    'redirectUri'             => 'http://localhost/Work-Related/Berans-Trading-Project/admin/public/callback.php',
    'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
    'urlAccessToken'          => 'https://identity.xero.com/connect/token',
    'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
]);

if (!isset($_GET['code'])) {
    exit('No code in URL');
}

try {
    $accessToken = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    $_SESSION['access_token'] = $accessToken->getToken();
    $_SESSION['refresh_token'] = $accessToken->getRefreshToken();
    $_SESSION['expires'] = $accessToken->getExpires();

    // ✅ Get Tenant ID
    $client = new \GuzzleHttp\Client();
    $response = $client->get('https://api.xero.com/connections', [
        'headers' => [
            'Authorization' => 'Bearer ' . $_SESSION['access_token'],
            'Accept'        => 'application/json',
        ]
    ]);

    $connections = json_decode($response->getBody(), true);

    if (!empty($connections)) {
        $_SESSION['tenant_id'] = $connections[0]['tenantId'];
        $pdo = openDB();
            $stmt = $pdo->prepare("
                UPDATE site_config SET 
                    xero_refresh_token = :xero_refresh_token,
                    xero_tenant_id = :xero_tenant_id,
                    xero_ttl = :xero_ttl
                WHERE company_id = 1
                LIMIT 1
            ");

            $stmt->execute([
                ':xero_refresh_token' => $_SESSION['refresh_token'],
                ':xero_tenant_id' => $_SESSION['tenant_id'],
                ':xero_ttl' => date('Y-m-d H:i:s', $_SESSION['expires'])
            ]);
            $_SESSION['success'] = '✅ Xero connected successfully!';
            header('Location: xero-main.php');
    } else {
        $_SESSION['failed'] = '❌ Fail to establish connection.';
        header('Location: xero-main.php');

    }
} catch (\Exception $e) {
        $_SESSION['failed'] = '❌ Fail to establish connection with error: ' . $e->getMessage()->getBody();
        header('Location: xero-main.php');
}
