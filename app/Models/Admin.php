<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDO;

final class Admin
{
    public static function count(): int
    {
        return (int) Database::pdo()->query('SELECT COUNT(*) FROM admins')->fetchColumn();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM admins WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM admins WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(string $name, string $email, string $password): int
    {
        $stmt = Database::pdo()->prepare('INSERT INTO admins (name, email, password_hash) VALUES (?, ?, ?)');
        $stmt->execute([$name, strtolower($email), password_hash($password, PASSWORD_DEFAULT)]);
        return (int) Database::pdo()->lastInsertId();
    }

    public static function updatePassword(int $id, string $password): void
    {
        $stmt = Database::pdo()->prepare('UPDATE admins SET password_hash = ? WHERE id = ?');
        $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
    }

    public static function recordFailure(int $id): void
    {
        $pdo = Database::pdo();
        $pdo->prepare('UPDATE admins SET failed_logins = failed_logins + 1 WHERE id = ?')->execute([$id]);
        $stmt = $pdo->prepare('SELECT failed_logins FROM admins WHERE id = ?');
        $stmt->execute([$id]);
        $fails = (int) $stmt->fetchColumn();
        if ($fails >= 6) {
            $pdo->prepare('UPDATE admins SET locked_until = DATE_ADD(NOW(), INTERVAL 20 MINUTE) WHERE id = ?')->execute([$id]);
        }
    }

    public static function recordSuccess(int $id, string $ip): void
    {
        $stmt = Database::pdo()->prepare(
            'UPDATE admins SET failed_logins = 0, locked_until = NULL, last_login_at = NOW(), last_login_ip = ? WHERE id = ?'
        );
        $stmt->execute([$ip, $id]);
    }
}
