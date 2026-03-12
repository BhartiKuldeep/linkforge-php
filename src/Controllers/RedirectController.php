<?php

declare(strict_types=1);

final class RedirectController
{
    public function __construct(
        private LinkService $service,
        private Flash $flash,
    ) {
    }

    public function handle(string $code): never
    {
        $link = $this->service->resolve($code);

        if ($link === null) {
            http_response_code(404);
            $this->renderMessage('Link not found', 'The short code you requested does not exist or may have been removed.');
        }

        if (is_link_expired($link)) {
            http_response_code(410);
            $this->renderMessage('Link expired', 'This short link has expired and can no longer be used.');
        }

        $this->service->registerClick($code);

        header('Location: ' . $link['original_url'], true, 302);
        exit;
    }

    private function renderMessage(string $title, string $message): never
    {
        echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>' . e($title) . '</title><style>body{margin:0;font-family:Inter,system-ui,sans-serif;background:#070d1c;color:#eef2ff;display:grid;place-items:center;min-height:100vh;padding:1rem}main{max-width:560px;background:#101933;border:1px solid rgba(255,255,255,.08);padding:2rem;border-radius:24px;box-shadow:0 18px 45px rgba(2,6,23,.35)}a{display:inline-block;margin-top:1rem;color:#c4b5fd;text-decoration:none;font-weight:700}</style></head><body><main><h1>' . e($title) . '</h1><p style="color:#a4afcc;line-height:1.7">' . e($message) . '</p><a href="/">Back to dashboard</a></main></body></html>';
        exit;
    }
}
