<?php
declare(strict_types=1);

namespace App;

final class View
{
    public static function render(string $template, array $data = [], string $layout = 'layouts/public'): void
    {
        $page = VIEW_PATH . '/' . $template . '.php';
        if (!is_file($page)) {
            throw new \RuntimeException('View not found.');
        }
        extract($data, EXTR_SKIP);
        $contentView = $page;
        $layoutFile = VIEW_PATH . '/' . $layout . '.php';
        if (!is_file($layoutFile)) {
            require $page;
            return;
        }
        require $layoutFile;
    }

    public static function partial(string $name, array $data = []): void
    {
        $file = VIEW_PATH . '/partials/' . $name . '.php';
        if (!is_file($file)) {
            return;
        }
        extract($data, EXTR_SKIP);
        require $file;
    }

    public static function email(string $name, array $data = []): string
    {
        $file = VIEW_PATH . '/emails/' . $name . '.php';
        ob_start();
        extract($data, EXTR_SKIP);
        require $file;
        return (string) ob_get_clean();
    }
}
