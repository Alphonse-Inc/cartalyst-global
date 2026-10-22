<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDO;

final class ProcurementRequest
{
    public static function create(array $data): array
    {
        $pdo = Database::pdo();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO procurement_requests (
                    reference, full_name, company_name, email, phone, country_city, category,
                    item_description, quantity, unit, budget_range, delivery_date, notes,
                    attachment_stored, attachment_original, attachment_mime, status, ip_hash, user_agent
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )'
            );
            $placeholder = 'TMP-' . strtoupper(bin2hex(random_bytes(8)));
            $stmt->execute([
                $placeholder,
                $data['full_name'],
                $data['company_name'],
                $data['email'],
                $data['phone'],
                $data['country_city'],
                $data['category'],
                $data['item_description'],
                $data['quantity'],
                $data['unit'],
                $data['budget_range'],
                $data['delivery_date'],
                $data['notes'],
                $data['attachment_stored'],
                $data['attachment_original'],
                $data['attachment_mime'],
                'new',
                $data['ip_hash'],
                $data['user_agent'],
            ]);
            $id = (int) $pdo->lastInsertId();
            $reference = sprintf('CG-%s-%06d', date('Y'), $id);
            $pdo->prepare('UPDATE procurement_requests SET reference = ? WHERE id = ?')->execute([$reference, $id]);
            $pdo->commit();
            $data['id'] = $id;
            $data['reference'] = $reference;
            $data['status'] = 'new';
            return $data;
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function paginate(string $status, string $q, int $page, int $perPage = 20): array
    {
        $where = ['1=1'];
        $params = [];
        if (in_array($status, ['new', 'in_progress', 'completed'], true)) {
            $where[] = 'status = ?';
            $params[] = $status;
        }
        if ($q !== '') {
            $where[] = '(reference LIKE ? OR full_name LIKE ? OR email LIKE ? OR company_name LIKE ? OR category LIKE ?)';
            $like = '%' . $q . '%';
            array_push($params, $like, $like, $like, $like, $like);
        }
        $sqlWhere = implode(' AND ', $where);
        $count = Database::pdo()->prepare("SELECT COUNT(*) FROM procurement_requests WHERE {$sqlWhere}");
        $count->execute($params);
        $total = (int) $count->fetchColumn();

        $offset = max(0, ($page - 1) * $perPage);
        $stmt = Database::pdo()->prepare(
            "SELECT * FROM procurement_requests WHERE {$sqlWhere} ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}"
        );
        $stmt->execute($params);
        return ['rows' => $stmt->fetchAll(), 'total' => $total, 'page' => $page, 'perPage' => $perPage];
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM procurement_requests WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function updateStatus(int $id, string $status): void
    {
        $stmt = Database::pdo()->prepare('UPDATE procurement_requests SET status = ? WHERE id = ?');
        $stmt->execute([$status, $id]);
    }

    public static function export(string $status, string $q): array
    {
        $result = self::paginate($status, $q, 1, 100000);
        return $result['rows'];
    }

    public static function counts(): array
    {
        $stmt = Database::pdo()->query(
            "SELECT status, COUNT(*) c FROM procurement_requests GROUP BY status"
        );
        $out = ['new' => 0, 'in_progress' => 0, 'completed' => 0, 'all' => 0];
        foreach ($stmt->fetchAll() as $row) {
            $out[$row['status']] = (int) $row['c'];
            $out['all'] += (int) $row['c'];
        }
        return $out;
    }
}
