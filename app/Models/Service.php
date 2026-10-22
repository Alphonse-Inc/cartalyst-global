<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDO;

final class Service
{
    public static function active(): array
    {
        $stmt = Database::pdo()->query('SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC, title ASC');
        return $stmt->fetchAll();
    }

    public static function all(): array
    {
        $stmt = Database::pdo()->query('SELECT * FROM services ORDER BY sort_order ASC, title ASC');
        return $stmt->fetchAll();
    }

    public static function findBySlug(string $slug): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM services WHERE slug = ? AND is_active = 1 LIMIT 1');
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM services WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function titles(): array
    {
        $out = [];
        foreach (self::active() as $row) {
            $out[] = $row['title'];
        }
        return $out;
    }

    public static function save(array $data, ?int $id = null): void
    {
        if ($id) {
            $stmt = Database::pdo()->prepare(
                'UPDATE services SET slug=?, title=?, short_description=?, description=?, icon=?, sort_order=?, is_active=? WHERE id=?'
            );
            $stmt->execute([
                $data['slug'], $data['title'], $data['short_description'], $data['description'],
                $data['icon'], $data['sort_order'], $data['is_active'], $id,
            ]);
            return;
        }
        $stmt = Database::pdo()->prepare(
            'INSERT INTO services (slug, title, short_description, description, icon, sort_order, is_active) VALUES (?,?,?,?,?,?,?)'
        );
        $stmt->execute([
            $data['slug'], $data['title'], $data['short_description'], $data['description'],
            $data['icon'], $data['sort_order'], $data['is_active'],
        ]);
    }

    public static function delete(int $id): void
    {
        Database::pdo()->prepare('DELETE FROM services WHERE id = ?')->execute([$id]);
    }
}
