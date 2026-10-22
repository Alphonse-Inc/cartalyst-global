<?php
declare(strict_types=1);

namespace App;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $host = config('db.host');
        $name = config('db.name');
        $user = config('db.user');
        $pass = config('db.pass');
        $charset = config('db.charset', 'utf8mb4');
        $dsn = "mysql:host={$host};dbname={$name};charset={$charset}";

        try {
            self::$pdo = new PDO($dsn, (string) $user, (string) $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            Logger::error('Database connection failed', ['message' => $e->getMessage()]);
            throw new PDOException('Database unavailable.', (int) $e->getCode());
        }

        return self::$pdo;
    }
}
