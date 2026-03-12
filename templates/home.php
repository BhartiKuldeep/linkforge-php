<?php
$stats = $dashboard['stats'];
$links = $dashboard['links'];
$clicks = $dashboard['clicks'];
$topLink = $stats['top_link'] ?? null;
?>

<section class="hero">
    <div>
        <div class="hero-badge">Responsive multi-file PHP portfolio project</div>
        <h1>Shorten, track, and manage links with a clean dashboard.</h1>
        <p>
            LinkForge is a complete URL shortener built with plain PHP and file-based storage.
            It supports custom aliases, expiration dates, click analytics, deletion, and polished responsive styling.
        </p>
        <div class="hero-points">
            <span class="pill">No database required</span>
            <span class="pill">Runs with <span class="code">php -S localhost:8000 router.php</span></span>
            <span class="pill">Short links like <span class="code"><?= e(app_url('hello123')); ?></span></span>
        </div>
        <?php require dirname(__DIR__) . '/templates/partials/flash.php'; ?>
    </div>

    <div class="panel form-panel">
        <div class="panel-title">
            <div>
                <h2>Create a short link</h2>
                <p>Add a long URL, optional custom alias, and optional expiration date.</p>
            </div>
        </div>

        <form action="<?= e(app_url('shorten')); ?>" method="post" class="grid">
            <input type="hidden" name="_csrf" value="<?= e($csrfToken); ?>">

            <div class="field">
                <label for="original_url">Long URL</label>
                <input class="input" type="url" id="original_url" name="original_url" placeholder="https://github.com/your-username/your-project" required>
                <div class="help-text">You can also paste a URL without <span class="code">https://</span>. The app will normalize it.</div>
            </div>

            <div class="grid grid-2">
                <div class="field">
                    <label for="custom_alias">Custom alias</label>
                    <input class="input" type="text" id="custom_alias" name="custom_alias" placeholder="my-portfolio-link" maxlength="40">
                    <div class="help-text">Allowed characters: letters, numbers, hyphen, underscore.</div>
                </div>

                <div class="field">
                    <label for="expires_at">Expiration date</label>
                    <input class="input" type="date" id="expires_at" name="expires_at">
                    <div class="help-text">Leave empty to keep the short link active forever.</div>
                </div>
            </div>

            <div class="form-actions">
                <button class="button button-primary" type="submit">Create short URL</button>
                <span class="help-text">New links appear below with click counts and management actions.</span>
            </div>
        </form>
    </div>
</section>

<section class="section stats-grid">
    <article class="panel stat-card">
        <small>Total links</small>
        <strong><?= e((string) $stats['total_links']); ?></strong>
        <span>All short links created in your local storage.</span>
    </article>
    <article class="panel stat-card">
        <small>Total clicks</small>
        <strong><?= e((string) $stats['total_clicks']); ?></strong>
        <span><?= e((string) $stats['today_clicks']); ?> click(s) recorded today across all links.</span>
    </article>
    <article class="panel stat-card">
        <small>Active links</small>
        <strong><?= e((string) $stats['active_links']); ?></strong>
        <span><?= e((string) $stats['expired_links']); ?> expired link(s) are no longer redirecting.</span>
    </article>
    <article class="panel stat-card">
        <small>Top short code</small>
        <strong><?= e($topLink['short_code'] ?? '—'); ?></strong>
        <span>
            <?php if ($topLink): ?>
                <?= e((string) ($topLink['clicks'] ?? 0)); ?> clicks on the most visited short link.
            <?php else: ?>
                Create your first short link to start collecting analytics.
            <?php endif; ?>
        </span>
    </article>
</section>

<section class="section content-grid">
    <div class="panel links-panel">
        <div class="panel-title">
            <div>
                <h3>Manage short links</h3>
                <p>Copy, open, review status, or delete any generated link.</p>
            </div>
        </div>

        <?php if (empty($links)): ?>
            <div class="empty-state">
                No links yet. Create one using the form above. This app stores all records in JSON files,
                which makes it easy to run locally and showcase on GitHub without extra setup.
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Short link</th>
                            <th>Original URL</th>
                            <th>Status</th>
                            <th>Stats</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($links as $link): ?>
                            <?php $shortUrl = app_url($link['short_code']); ?>
                            <?php $expired = is_link_expired($link); ?>
                            <tr>
                                <td>
                                    <div class="link-stack">
                                        <a class="link-main" href="<?= e($shortUrl); ?>" target="_blank" rel="noopener noreferrer"><?= e($shortUrl); ?></a>
                                        <span class="link-sub">Created <?= e(format_relative($link['created_at'] ?? null)); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="link-stack">
                                        <span class="link-main"><?= e(mask_user_agent(parse_url($link['original_url'], PHP_URL_HOST) ?: $link['original_url'])); ?></span>
                                        <span class="link-sub"><?= e($link['original_url']); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($expired): ?>
                                        <span class="badge badge-danger">Expired</span>
                                    <?php elseif (!empty($link['expires_at'])): ?>
                                        <span class="badge badge-warning">Expires <?= e((new DateTimeImmutable($link['expires_at']))->format('d M Y')); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="link-stack">
                                        <span class="link-main"><?= e((string) ($link['clicks'] ?? 0)); ?> click(s)</span>
                                        <span class="link-sub">Last clicked: <?= e(format_relative($link['last_clicked_at'] ?? null)); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="inline-actions">
                                        <button class="mini-button" type="button" data-copy="<?= e($shortUrl); ?>">Copy</button>
                                        <a class="mini-button" href="<?= e($shortUrl); ?>" target="_blank" rel="noopener noreferrer">Visit</a>
                                        <form action="<?= e(app_url('delete/' . $link['short_code'])); ?>" method="post" onsubmit="return confirm('Delete this short link?');">
                                            <input type="hidden" name="_csrf" value="<?= e($csrfToken); ?>">
                                            <button class="mini-button button-danger" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <aside class="panel log-panel">
        <div class="panel-title">
            <div>
                <h3>Recent click activity</h3>
                <p>Latest visits are recorded with referrer, IP, timestamp, and device info.</p>
            </div>
        </div>

        <?php if (empty($clicks)): ?>
            <div class="empty-state">
                Click logs will appear here after someone opens one of your short URLs.
            </div>
        <?php else: ?>
            <div class="log-list">
                <?php foreach ($clicks as $click): ?>
                    <article class="log-item">
                        <h4><?= e(app_url($click['short_code'])); ?></h4>
                        <p>
                            IP: <?= e($click['ip_address'] ?? 'Unknown'); ?><br>
                            Referrer: <?= e($click['referrer'] ?? 'Direct'); ?>
                        </p>
                        <small>
                            <?= e(format_datetime($click['clicked_at'] ?? null)); ?> • <?= e(mask_user_agent($click['user_agent'] ?? null)); ?>
                        </small>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </aside>
</section>
