<?php

return array(
    'smtp_details' => array(
        'name' => 'koolguru.co.in',
        'host' => 'sg2plcpnl0077.prod.sin2.secureserver.net',
        'connection_class' => 'login',
        'port' => '465',
        'connection_config' => array(
            'ssl' => 'ssl', /* Page would hang without this line being added */
            'username' => 'support@koolguru.co.in',
            'password' => 'koolguru@123',
        ),
    ),
    'payu_config' => array(
        // Merchant key here as provided by Payu
        'merchant_key' => 'gtKFFx',
        // Merchant Salt as provided by Payu
        'merchant_salt' => 'eCwWELxi',
        // End point - change to https://secure.payu.in for LIVE mode
        'payu_base_url' => 'https://test.payu.in',
    )
);
?>
