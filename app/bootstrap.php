<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('UPLOAD_PATH', BASE_PATH . '/uploads');
define('LOG_PATH', BASE_PATH . '/logs');
define('VIEW_PATH', APP_PATH . '/Views');
define('VENDOR_PATH', BASE_PATH . '/vendor');

require APP_PATH . '/helpers.php';
load_env_file(CONFIG_PATH . '/.env');

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = APP_PATH . '/' . $relative . '.php';
    if (is_file($file)) {
        require $file;
    }
});

date_default_timezone_set((string) config('timezone', 'UTC'));
error_reporting(E_ALL);
ini_set('display_errors', config('debug') ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', LOG_PATH . '/php-error.log');

App\Logger::register();
App\Session::start();

$path = current_path();
if (!is_file(CONFIG_PATH . '/config.php') && $path !== '/setup' && PHP_SAPI !== 'cli') {
    header('Location: ' . local_url('setup'));
    exit;
}
