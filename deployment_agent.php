#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class DeploymentAgent {
    private $config;
    private $logFile;
    
    public function __construct($config) {
        $this->config = $config;
        $this->logFile = $config['paths']['log'];
        $this->ensureDir($config['paths']['app']);
        $this->ensureDir($config['paths']['download']);
        $this->log("Deployment Agent starting...");
        $this->log("Environment: " . $config['environment']);
    }
    
    private function ensureDir($dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
    
    private function log($message) {
        $timestamp = date('Y-m-d H:i:s');
        $line = "[$timestamp] $message\n";
        echo $line;
        file_put_contents($this->logFile, $line, FILE_APPEND);
    }
    
    private function downloadBundle($remotePath) {
        $filename = basename($remotePath);
        $localPath = $this->config['paths']['download'] . '/' . $filename;
        
        $this->log("Downloading: $filename");
        
        $remote = sprintf(
            "%s@%s:%s",
            $this->config['deployment_vm']['user'],
            $this->config['deployment_vm']['host'],
            $remotePath
        );
        
        $cmd = sprintf("rsync -avz %s %s 2>&1", 
            escapeshellarg($remote), 
            escapeshellarg($localPath)
        );
        
        exec($cmd, $output, $returnCode);
        
        if ($returnCode !== 0) {
            throw new Exception("Download failed: " . implode("\n", $output));
        }
        
        if (!file_exists($localPath)) {
            throw new Exception("Bundle file not found after download");
        }
        
        $this->log("downloaded to $localPath");
        return $localPath;
    }
    
    private function verifyHash($filepath, $expectedHash) {
        $this->log("Verifying integrity...");
        
        $actualHash = hash_file('sha256', $filepath);
        
        if ($actualHash !== $expectedHash) {
            throw new Exception("Hash mismatch");
        }
        
        $this->log("verified");
        return true;
    }
    
    private function backup() {
        $appDir = $this->config['paths']['app'];
        
        if (!is_dir($appDir) || count(scandir($appDir)) <= 2) {
            $this->log("No app to backup");
            return null;
        }
        
        $backupDir = $appDir . '_backup_' . time();
        $this->log("made backup @ backup: $backupDir");
        
        exec("cp -r " . escapeshellarg($appDir) . " " . escapeshellarg($backupDir));
        
        return $backupDir;
    }
    
    private function extractBundle($bundlePath) {
        $appDir = $this->config['paths']['app'];
        
        $this->log("Extracting to: $appDir");
 
        exec("rm -rf " . escapeshellarg($appDir) . "/*");
 
        $cmd = sprintf(
            "tar -xzf %s -C %s 2>&1",
            escapeshellarg($bundlePath),
            escapeshellarg($appDir)
        );
        
        exec($cmd, $output, $returnCode);
        
        if ($returnCode !== 0) {
            throw new Exception("extract fail: " . implode("\n", $output));
        }
        
        $this->log("extracted successfully");
    }
    
    private function runPostDeploy() {
        $script = $this->config['paths']['app'] . '/scripts/post_deploy.sh';
        
        if (file_exists($script)) {
            $this->log("Running post-deploy script...");
            exec("bash " . escapeshellarg($script));
            $this->log("✓ Post-deploy complete");
        }
    }
    
    private function restartService() {
        $service = $this->config['service_name'];
        
        $this->log("Restarting service: $service");
        
        exec("sudo systemctl restart $service 2>&1", $output, $returnCode);
        
        if ($returnCode === 0) {
            $this->log("restart succsess");
        } else {
            $this->log("restart failed");
        }
    }
    
    private function handleDeployment($ch, $msg) {
        $backupDir = null;
        
        try {
            $data = json_decode($msg->body, true);
            $this->log("NEW DEPLOYMENT");
            $this->log("Deployment ID: " . $data['deployment_id']);
            $this->log("Bundle: {$data['bundle_name']} v{$data['version']}");
            $this->log("Environment: " . $data['environment']);
        
            if ($data['environment'] !== $this->config['environment']) {
             
                return;
            }
            $backupDir = $this->backup();
            $localBundle = $this->downloadBundle($data['file_path']);
            $this->verifyHash($localBundle, $data['file_hash']);
            
            $this->extractBundle($localBundle);
            
            $this->runPostDeploy();
         
            $this->restartService();

            $this->log("DEPLOYMENT SUCCESSFUL");

            
            $msg->ack();
            
        } catch (Exception $e) {

            $this->log("DEPLOYMENT FAILED");
            $this->log("Error: " . $e->getMessage());
            
        
            if ($backupDir && is_dir($backupDir)) {
                $appDir = $this->config['paths']['app'];
                $this->log("Restoring backup");
                exec("rm -rf " . escapeshellarg($appDir) . " && mv " . escapeshellarg($backupDir) . " " . escapeshellarg($appDir));
                $this->log("Backup restor succses");
            }
            
            $msg->nack(false);
        }
    }
    
    public function start() {
        $queue = 'deploy_' . $this->config['environment'];
        
        $rmq = $this->config['rabbitmq'];
        
        $this->log("Connecting to RabbitMQ: {$rmq['host']}:{$rmq['port']}");
        
        $connection = new AMQPStreamConnection(
            $rmq['host'],
            $rmq['port'],
            $rmq['user'],
            $rmq['pass']
        );
        
        $channel = $connection->channel();
        $channel->queue_declare($queue, false, true, false, false);
        $channel->basic_qos(null, 1, null);
        $this->log("AGENT READY");
        $this->log("Listening on: $queue");

        $callback = function($msg) use ($channel) {
            $this->handleDeployment($channel, $msg);
        };
        
        $channel->basic_consume($queue, '', false, false, false, false, $callback);
        
        try {
            while ($channel->is_consuming()) {
                $channel->wait();
            }
        } catch (Exception $e) {
            $this->log("Error: " . $e->getMessage());
        }
        
        $channel->close();
        $connection->close();
    }
}

$config = require __DIR__ . '/config.php';
$agent = new DeploymentAgent($config);
$agent->start();
?>
