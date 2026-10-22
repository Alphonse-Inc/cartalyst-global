<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDO;

final class Review
{
    public static function create(array $data): int
    {
        $stmt = Database::pdo()->prepare(
            'INSERT INTO reviews (name, company, job_title, email, rating, title, body, status, ip_hash)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['name'],
            $data['company'],
            $data['job_title'],
            $data['email'],
            $data['rating'],
            $data['title'],
            $data['body'],
            'pending',
            $data['ip_hash'],
        ]);
        return (int) Database::pdo()->lastInsertId();
    }

    public static function approved(int $limit, int $offset = 0): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT id, name, company, job_title, rating, title, body, featured, created_at
             FROM reviews WHERE status = ? ORDER BY featured DESC, created_at DESC LIMIT ' . (int) $limit . ' OFFSET ' . (int) $offset
        );
        $stmt->execute(['approved']);
        return $stmt->fetchAll();
    }

    public static function featured(int $limit = 8): array
    {
        $stmt = Database::pdo()->prepare(
            'SELECT id, name, company, job_title, rating, title, body, created_at
             FROM reviews WHERE status = ? AND featured = 1 ORDER BY created_at DESC LIMIT ' . (int) $limit
        );
        $stmt->execute(['approved']);
        $rows = $stmt->fetchAll();
        if ($rows) {
            return $rows;
        }
        return self::approved($limit, 0);
    }

    public static function stats(): array
    {
        $stmt = Database::pdo()->query(
            "SELECT COUNT(*) AS c, AVG(rating) AS a FROM reviews WHERE status = 'approved'"
        );
        $row = $stmt->fetch() ?: ['c' => 0, 'a' => 0];
        return [
            'count' => (int) $row['c'],
            'average' => $row['c'] ? number_format((float) $row['a'], 1) : '0.0',
        ];
    }

    public static function recentDuplicate(string $email, string $body): bool
    {
        $stmt = Database::pdo()->prepare(
            'SELECT id FROM reviews WHERE email = ? AND body = ? AND created_at > (NOW() - INTERVAL 7 DAY) LIMIT 1'
        );
        $stmt->execute([$email, $body]);
        return (bool) $stmt->fetch();
    }

    public static function paginateAdmin(string $status, int $page, int $perPage = 20): array
    {
        $where = '1=1';
        $params = [];
        if (in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $where = 'status = ?';
            $params[] = $status;
        }
        $count = Database::pdo()->prepare("SELECT COUNT(*) FROM reviews WHERE {$where}");
        $count->execute($params);
        $total = (int) $count->fetchColumn();
        $offset = max(0, ($page - 1) * $perPage);
        $stmt = Database::pdo()->prepare(
            "SELECT * FROM reviews WHERE {$where} ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}"
        );
        $stmt->execute($params);
        return ['rows' => $stmt->fetchAll(), 'total' => $total];
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM reviews WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function setStatus(int $id, string $status): void
    {
        Database::pdo()->prepare('UPDATE reviews SET status = ? WHERE id = ?')->execute([$status, $id]);
    }

    public static function update(int $id, array $data): void
    {
        $stmt = Database::pdo()->prepare(
            'UPDATE reviews SET name=?, company=?, job_title=?, rating=?, title=?, body=?, featured=?, status=? WHERE id=?'
        );
        $stmt->execute([
            $data['name'], $data['company'], $data['job_title'], $data['rating'],
            $data['title'], $data['body'], $data['featured'], $data['status'], $id,
        ]);
    }

    public static function delete(int $id): void
    {
        Database::pdo()->prepare('DELETE FROM reviews WHERE id = ?')->execute([$id]);
    }

    public static function pendingCount(): int
    {
        return (int) Database::pdo()->query("SELECT COUNT(*) FROM reviews WHERE status = 'pending'")->fetchColumn();
    }
}
