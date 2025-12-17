#!/usr/bin/env php
<?php
//DEV SCRIPT
// follow Dev setup instuctions in tech doc
//script will send a rabbitmq msg to deployvm which executes an rsync with params passed from script. 
//WILL NOT SEND LAYERED FOLDERS **USE ON SOURCE CODE ONLY*
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
$DEPLOYMENT_VM_IP = '100.86.240.90'; 
$RABBITMQ_USER = 'deploy_user';
$RABBITMQ_PASS = 'deploypass';
$BUNDLE_NAME = 'devtest1';
if ($argc < 3) {
    echo "invalid params example: php notify_deployment.php 1.0.5 'Bug fixes'\n\n";
    exit(1);
}

$version = $argv[1];
$description = $argv[2];

$sourcePath = getcwd();
$devIP = trim(shell_exec("ip -o -4 addr show tailscale0 | awk '{print $4}' | cut -d/ -f1"));

echo "Bundle Name: $BUNDLE_NAME\n";
echo "Version: $version\n";
echo "Description: $description\n";
echo "Src Path: $sourcePath\n";
echo "Dev Machine IP: $devIP\n";
echo "Deployment VM: $DEPLOYMENT_VM_IP\n\n";

try {
    $connection = new AMQPStreamConnection(
        $DEPLOYMENT_VM_IP,
        5672,
        $RABBITMQ_USER,
        $RABBITMQ_PASS
    );
    
    $channel = $connection->channel();

    $channel->queue_declare('code_pull_requests', false, true, false, false);

    $message = [
        'bundle_name' => $BUNDLE_NAME,
        'version' => $version,
        'description' => $description,
        'dev_ip' => $devIP,
        'source_path' => $sourcePath,
        'timestamp' => date('c')
    ];

    $msg = new AMQPMessage(
        json_encode($message),
        ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
    );
    
    $channel->basic_publish($msg, '', 'code_pull_requests');
    
    echo "✓ Notification sent\n";
    
    $channel->close();
    $connection->close();
    
    exit(0);
    
} catch (Exception $e) {
    echo " Error: " . $e->getMessage() . "\n\n";
    exit(1);
}
?>

