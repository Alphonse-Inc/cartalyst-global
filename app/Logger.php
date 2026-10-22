<?php
declare(strict_types=1);

namespace App;

use Throwable;

final class Logger
{
    public static function register(): void
    {
        set_exception_handler(static function (Throwable $e): void {
            self::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
            if (PHP_SAPI === 'cli') {
                fwrite(STDERR, $e->getMessage() . PHP_EOL);
                exit(1);
            }
            http_response_code(500);
            if (config('debug')) {
                echo '<pre>' . e($e->getMessage() . "\n" . $e->getTraceAsString()) . '</pre>';
                exit;
            }
            echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Something went wrong | Cartalyst Global</title></head><body style="font-family:system-ui;padding:2rem;background:#F6F3EC;color:#0B1F3A;"><h1>Something went wrong</h1><p>Please try again later.</p></body></html>';
            exit;
        });
    }

    public static function error(string $message, array $context = []): void
    {
        $line = date('c') . ' ' . $message;
        if ($context) {
            $line .= ' ' . json_encode($context, JSON_UNESCAPED_SLASHES);
        }
        $file = LOG_PATH . '/app.log';
        @file_put_contents($file, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
