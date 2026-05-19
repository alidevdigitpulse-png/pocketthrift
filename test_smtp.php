<?php
// test_smtp.php

require __DIR__ . '/vendor/autoload.php';

$host = 'smtp.titan.email';
$port = 587;
$username = 'contact@pocketthrift.com';
$password = '0mh&s1Qttskowisrla'; // The password user provided
$encryption = 'tls';

echo "Testing SMTP connection to $host:$port ($encryption)...\n";

try {
    $transport = (new Swift_SmtpTransport($host, $port, $encryption))
        ->setUsername($username)
        ->setPassword($password);

    $mailer = new Swift_Mailer($transport);

    $logger = new Swift_Plugins_Loggers_ArrayLogger();
    $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

    echo "Attempting to start connection...\n";
    $transport->start();
    echo "Connection successful!\n";
    echo "Credentials are valid.\n";
} catch (Exception $e) {
    echo "Connection failed!\n";
    echo "Error: " . $e->getMessage() . "\n";
    
    // Attempt standard socket connection for low-level debug if available
    echo "\n--- Low Level Socket Check ---\n";
    $fp = fsockopen($host, $port, $errno, $errstr, 10);
    if (!$fp) {
        echo "Socket connection failed: $errstr ($errno)\n";
    } else {
        echo "Socket connection opened successfully.\n";
        $response = fgets($fp, 512);
        echo "Server greeting: $response";
        fclose($fp);
    }
}
