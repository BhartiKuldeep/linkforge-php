<?php

declare(strict_types=1);

final class View
{
    public function render(string $template, array $data = [], string $layout = 'layouts/app'): void
    {
        $templatePath = dirname(__DIR__, 2) . '/templates/' . $template . '.php';
        $layoutPath = dirname(__DIR__, 2) . '/templates/' . $layout . '.php';

        if (!file_exists($templatePath) || !file_exists($layoutPath)) {
            throw new RuntimeException('View template not found.');
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $templatePath;
        $content = (string) ob_get_clean();

        require $layoutPath;
    }
}
