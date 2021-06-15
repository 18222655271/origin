<?php
/*内部通信服务*/

use GatewayWorker\Gateway;
use Workerman\Autoloader;
use Workerman\Worker;

// 自动加载类
require_once __DIR__ . '/../../vendor/autoload.php';
Autoloader::setRootPath(__DIR__);

$kefu_config = get_addon_config('kefu');

$internal_gateway                  = new Gateway("Text://127.0.0.1:" . ($kefu_config['register_port'] + 100));
$internal_gateway->name            = 'KeFuGateway';
$internal_gateway->startPort       = $kefu_config['internal_start_port'] + 1000;
$internal_gateway->registerAddress = '127.0.0.1:' . $kefu_config['register_port'];// 端口为start_register.php中监听的端口

// 如果不是在根目录启动，则运行runAll方法
if (!defined('GLOBAL_START')) {
    Worker::runAll();
}