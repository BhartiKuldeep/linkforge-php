<?php

declare(strict_types=1);

function config_value(string $key, mixed $default = null): mixed
{
    global $config;

    return $config[$key] ?? $default;
}

function ensure_storage_files(array $config): void
{
    if (!is_dir($config['storage_path'])) {
        mkdir($config['storage_path'], 0777, true);
    }

    foreach ([$config['links_file'], $config['clicks_file']] as $file) {
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
    }
}

function e(string|null $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function current_path(): string
{
    return parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
}

function base_url(): string
{
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ((int) ($_SERVER['SERVER_PORT'] ?? 80) === 443);
    $scheme = $isHttps ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';

    return $scheme . '://' . $host;
}

function redirect_to(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function app_url(string $path = ''): string
{
    $path = ltrim($path, '/');

    return rtrim(base_url(), '/') . ($path !== '' ? '/' . $path : '');
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(24));
    }

    return $_SESSION['_csrf'];
}

function verify_csrf(?string $token): bool
{
    return is_string($token) && hash_equals($_SESSION['_csrf'] ?? '', $token);
}

function format_datetime(?string $value): string
{
    if (!$value) {
        return '—';
    }

    try {
        return (new DateTimeImmutable($value))->format('d M Y, h:i A');
    } catch (Throwable) {
        return '—';
    }
}

function format_relative(?string $value): string
{
    if (!$value) {
        return 'Never';
    }

    try {
        $date = new DateTimeImmutable($value);
        $now = new DateTimeImmutable();
        $diff = $now->getTimestamp() - $date->getTimestamp();

        if ($diff < 60) {
            return 'just now';
        }

        if ($diff < 3600) {
            return floor($diff / 60) . ' min ago';
        }

        if ($diff < 86400) {
            return floor($diff / 3600) . ' hr ago';
        }

        if ($diff < 604800) {
            return floor($diff / 86400) . ' day ago';
        }

        return $date->format('d M Y');
    } catch (Throwable) {
        return 'Never';
    }
}

function is_link_expired(array $link): bool
{
    if (empty($link['expires_at'])) {
        return false;
    }

    try {
        return new DateTimeImmutable($link['expires_at']) < new DateTimeImmutable();
    } catch (Throwable) {
        return false;
    }
}

function mask_user_agent(?string $value): string
{
    if (!$value) {
        return 'Unknown device';
    }

    return mb_strimwidth($value, 0, 82, '...');
}

function client_ip(): string
{
    $keys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];

    foreach ($keys as $key) {
        $value = $_SERVER[$key] ?? null;

        if (is_string($value) && trim($value) !== '') {
            $parts = explode(',', $value);
            return trim($parts[0]);
        }
    }

    return 'Unknown';
}
