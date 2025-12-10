<?php

return [

    'environment' => 'dev',

    'rabbitmq' => [
        'host' => '100.86.240.90',  // CHANGE to your deployment VM IP
        'port' => 5672,
        'user' => 'deploy_user',
        'pass' => 'deploypass'
    ],

    'deployment_vm' => [
        'host' => '100.115.148.27',
        'user' => 'deploy'
    ],

    'paths' => [
        'app' => '/var/www/myapp',
        'download' => '/tmp/deployments',
        'log' => '/opt/deployment-agent/deployment.log'
    ],

    'service_name' => 'test'
];
?>
