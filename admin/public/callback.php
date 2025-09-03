<?php
require 'vendor/autoload.php';

use League\OAuth2\Client\Provider\GenericProvider;

session_start();

$provider = new GenericProvider([
    'clientId'                => '401AA121B0D7485193B11FA9CCA0546B',
    'clientSecret'            => 'Frpko_qPMO1nY2kMbHsnW5dqLssheS_oeNFrxvxh6k7vIpTu',
    'redirectUri'             => 'http://localhost/Work%20Related/Berans-Trading-Project/admin/public/callback.php',
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
        echo "✅ Connected to Xero!<br>";
        echo "Tenant ID: " . $_SESSION['tenant_id'] . "<br> ";
        echo "Access Token: " . $_SESSION['access_token'];
    } else {
        echo "❌ No active Xero tenant connection found.";
    }
} catch (\Exception $e) {
    echo "Failed to get access token or tenant ID: " . $e->getMessage();
}
