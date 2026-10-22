<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDO;

final class Setting
{
    public static function all(): array
    {
        $stmt = Database::pdo()->query('SELECT setting_key, setting_value FROM settings');
        $out = [];
        foreach ($stmt->fetchAll() as $row) {
            $out[$row['setting_key']] = $row['setting_value'];
        }
        return $out;
    }

    public static function put(string $key, string $value): void
    {
        $stmt = Database::pdo()->prepare(
            'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
        );
        $stmt->execute([$key, $value]);
    }
}
