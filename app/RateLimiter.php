<?php
declare(strict_types=1);

namespace App;

use PDO;

final class RateLimiter
{
    public static function tooMany(string $action): bool
    {
        $limits = config('rate_limits.' . $action, ['hits' => 5, 'window' => 3600]);
        $max = (int) $limits['hits'];
        $window = (int) $limits['window'];
        $hash = ip_hash();
        $start = date('Y-m-d H:i:s', intdiv(time(), $window) * $window);

        $pdo = Database::pdo();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('SELECT hits FROM rate_limits WHERE action = ? AND ip_hash = ? AND window_start = ? FOR UPDATE');
            $stmt->execute([$action, $hash, $start]);
            $row = $stmt->fetch();
            if (!$row) {
                $ins = $pdo->prepare('INSERT INTO rate_limits (action, ip_hash, hits, window_start) VALUES (?, ?, 1, ?)');
                $ins->execute([$action, $hash, $start]);
                $pdo->commit();
                return false;
            }
            $hits = (int) $row['hits'];
            if ($hits >= $max) {
                $pdo->commit();
                return true;
            }
            $upd = $pdo->prepare('UPDATE rate_limits SET hits = hits + 1 WHERE action = ? AND ip_hash = ? AND window_start = ?');
            $upd->execute([$action, $hash, $start]);
            $pdo->commit();
            return false;
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            Logger::error('Rate limiter failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function prune(): void
    {
        try {
            Database::pdo()->exec('DELETE FROM rate_limits WHERE window_start < (NOW() - INTERVAL 2 DAY)');
        } catch (\Throwable) {
            // ignore
        }
    }
}
