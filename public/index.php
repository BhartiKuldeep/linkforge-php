<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/');
$segments = $path === '' ? [] : explode('/', $path);

if ($method === 'GET' && empty($segments)) {
    $homeController->index();
    return;
}

if ($method === 'POST' && ($segments[0] ?? '') === 'shorten') {
    $homeController->store();
    return;
}

if ($method === 'POST' && ($segments[0] ?? '') === 'delete' && isset($segments[1])) {
    $deleteController->destroy($segments[1]);
    return;
}

if ($method === 'GET' && !empty($segments)) {
    $redirectController->handle($segments[0]);
    return;
}

http_response_code(404);
echo 'Page not found';
