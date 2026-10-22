<?php
/**
 * Convenience entry if /admin is ever mapped here.
 * Preferred access is https://your-domain/admin via public/index.php.
 */
declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';
header('Location: ' . base_url('admin'));
exit;
