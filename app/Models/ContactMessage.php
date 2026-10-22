<?php
declare(strict_types=1);

namespace App\Models;

use App\Database;

final class ContactMessage
{
    public static function create(array $data): int
    {
        $stmt = Database::pdo()->prepare(
            'INSERT INTO contact_messages (name, email, phone, subject, message, ip_hash) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['subject'],
            $data['message'],
            $data['ip_hash'],
        ]);
        return (int) Database::pdo()->lastInsertId();
    }
}
