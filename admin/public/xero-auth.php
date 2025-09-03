<?php
require 'vendor/autoload.php';

use XeroAPI\XeroPHP\XeroPHP;
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

$authorizationUrl = $provider->getAuthorizationUrl([
    'scope' => 'openid profile email accounting.transactions accounting.contacts accounting.settings'
]);

$_SESSION['oauth2state'] = $provider->getState();
header('Location: ' . $authorizationUrl);
exit;
