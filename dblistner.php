#!/usr/bin/php
<?php
echo "ears open";
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('login.php.inc');

function doLogin($username, $password)
{
    $login = new loginDB();
    return $login->validateLogin($username, $password);
}

function requestProcessor($request)
{
    echo "Received request" . PHP_EOL;
    var_dump($request);

    if (!isset($request['type'])) {
        return "ERROR: unsupported message type";
    }

    switch ($request['type']) {
        case "login":
            return doLogin($request['username'], $request['password']);
        default:
            return array("returnCode" => '0', 'message' => "Unknown request type");
    }
}

$server = new rabbitMQServer("testRabbitMQ.ini", "testServer");
echo " [*] Waiting for requests..." . PHP_EOL;
$server->process_requests('requestProcessor');
echo " [x] Server shutting down..." . PHP_EOL;
exit();
?>

