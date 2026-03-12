<?php

declare(strict_types=1);

session_start();

$config = require __DIR__ . '/config/app.php';

date_default_timezone_set($config['timezone']);

require_once __DIR__ . '/src/Support/helpers.php';
require_once __DIR__ . '/src/Support/Flash.php';
require_once __DIR__ . '/src/Support/View.php';
require_once __DIR__ . '/src/Storage/JsonStorage.php';
require_once __DIR__ . '/src/Repositories/LinkRepository.php';
require_once __DIR__ . '/src/Services/ShortCodeGenerator.php';
require_once __DIR__ . '/src/Services/UrlValidator.php';
require_once __DIR__ . '/src/Services/LinkService.php';
require_once __DIR__ . '/src/Controllers/HomeController.php';
require_once __DIR__ . '/src/Controllers/RedirectController.php';
require_once __DIR__ . '/src/Controllers/DeleteController.php';

ensure_storage_files($config);

$storage = new JsonStorage();
$linkRepository = new LinkRepository($storage, $config['links_file'], $config['clicks_file']);
$urlValidator = new UrlValidator();
$shortCodeGenerator = new ShortCodeGenerator($linkRepository);
$linkService = new LinkService($linkRepository, $urlValidator, $shortCodeGenerator);
$view = new View();
$flash = new Flash();

$homeController = new HomeController($linkService, $view, $flash);
$redirectController = new RedirectController($linkService, $flash);
$deleteController = new DeleteController($linkService, $flash);
