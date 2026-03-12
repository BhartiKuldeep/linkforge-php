<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'LinkForge'); ?></title>
    <meta name="description" content="LinkForge is a responsive PHP URL shortener with analytics, custom aliases, expirations, and a modern dashboard.">
    <link rel="stylesheet" href="<?= e(app_url('assets/css/style.css')); ?>">
</head>
<body>
    <div class="site-shell">
        <div class="container">
            <header class="topbar">
                <a class="brand" href="<?= e(app_url()); ?>">
                    <span class="brand-mark">LF</span>
                    <span class="brand-copy">
                        <strong>LinkForge</strong>
                        <span>PHP URL shortener with analytics</span>
                    </span>
                </a>
                <div class="topbar-actions">
                    <span>Custom aliases</span>
                    <span>•</span>
                    <span>Expiry dates</span>
                    <span>•</span>
                    <span>Click tracking</span>
                </div>
            </header>

            <?= $content; ?>

            <footer class="footer">
                Built for GitHub portfolio use with plain PHP, JSON storage, clean routing, and responsive UI.
            </footer>
        </div>
    </div>

    <script src="<?= e(app_url('assets/js/app.js')); ?>"></script>
</body>
</html>
