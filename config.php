<?php
// config.php - Deployment Agent Configuration

return [
    // Which environment is this? (dev, qa, or prod)
    'environment' => 'qa',
    
    // RabbitMQ connection (on deployment VM)
    'rabbitmq' => [
        'host' => '100.86.240.90',  // CHANGE to your deployment VM IP
        'port' => 5672,
        'user' => 'deploy_user',
        'pass' => 'deploypass'
    ],
    
    // Deployment VM connection
    'deployment_vm' => [
        'host' => '100.115.148.27',
        'user' => 'deploy'
    ],
    
    // Local paths
    'paths' => [
        'app' => '/var/www/myapp',
        'download' => '/tmp/deployments',
        'log' => '/opt/deployment-agent/deployment.log'
    ],
    
    // Application service name (optional)
    'service_name' => 'myapp'
];
?>
