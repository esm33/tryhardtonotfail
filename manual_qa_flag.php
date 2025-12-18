#!/usr/bin/env php
<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
if ($argc < 4) {
    echo "Usage: php manual_qa_flag.php <bundle_id> <good|bad> <message>\n";
    echo "  php manual_qa_flag.php 5 good 'All features tested, no issues found'\n";
    echo "  php manual_qa_flag.php 6 bad 'Login button not working'\n";
    echo "\n";
    exit(1);
}

$bundleId = $argv[1];
$flag = strtolower($argv[2]);
$message = $argv[3];

if (!in_array($flag, ['good', 'bad'])) {
    echo "\nError: Flag must be 'good' or 'bad'\n\n";
    exit(1);
}

$passed = ($flag === 'good');
echo "Bundle ID: $bundleId\n";
echo "Flag:      " . strtoupper($flag);
echo "Message:   $message\n";
echo "Tester:    " . get_current_user() . "\n";
echo "\n";
$config = require __DIR__ . '/config.php';

try {
    $rmq = $config['rabbitmq'];
    
    echo "Connecting to RabbitMQ on deployment VM...\n";
    
    $connection = new AMQPStreamConnection(
        $rmq['host'],
        $rmq['port'],
        $rmq['user'],
        $rmq['pass']
    );
    $channel = $connection->channel();
    $channel->queue_declare('qa_results', false, true, false, false);
    $qaMessage = [
        'bundle_id' => (int)$bundleId,
        'qa_passed' => $passed,
        'qa_type' => 'manual',
        'qa_message' => $message,
        'qa_tester' => get_current_user(),
        'environment' => $config['environment'],
        'timestamp' => date('c')
    ];
    $msg = new AMQPMessage(
        json_encode($qaMessage),
        ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
    );
    
    $channel->basic_publish($msg, '', 'qa_results');
    
    echo "QA result sent to deployment server\n";
    
    $channel->close();
    $connection->close();
    
    exit(0);
    
} catch (Exception $e) {
    echo "\nError: " . $e->getMessage() . "\n\n";
    exit(1);
}
?>
