<?php

declare(strict_types=1);

final class LinkRepository
{
    public function __construct(
        private JsonStorage $storage,
        private string $linksFile,
        private string $clicksFile,
    ) {
    }

    public function allLinks(): array
    {
        $links = $this->storage->read($this->linksFile);
        usort($links, fn (array $a, array $b): int => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));
        return $links;
    }

    public function allClicks(): array
    {
        $clicks = $this->storage->read($this->clicksFile);
        usort($clicks, fn (array $a, array $b): int => strcmp($b['clicked_at'] ?? '', $a['clicked_at'] ?? ''));
        return $clicks;
    }

    public function findByCode(string $code): ?array
    {
        foreach ($this->allLinks() as $link) {
            if (($link['short_code'] ?? '') === $code) {
                return $link;
            }
        }

        return null;
    }

    public function codeExists(string $code): bool
    {
        return $this->findByCode($code) !== null;
    }

    public function create(array $payload): array
    {
        $links = $this->allLinks();
        $links[] = $payload;
        $this->storage->write($this->linksFile, $links);
        return $payload;
    }

    public function updateByCode(string $code, callable $callback): ?array
    {
        $links = $this->allLinks();
        $updated = null;

        foreach ($links as $index => $link) {
            if (($link['short_code'] ?? '') !== $code) {
                continue;
            }

            $links[$index] = $callback($link);
            $updated = $links[$index];
            break;
        }

        if ($updated !== null) {
            $this->storage->write($this->linksFile, $links);
        }

        return $updated;
    }

    public function deleteByCode(string $code): bool
    {
        $links = $this->allLinks();
        $before = count($links);
        $remaining = array_values(array_filter($links, fn (array $link): bool => ($link['short_code'] ?? '') !== $code));

        if ($before === count($remaining)) {
            return false;
        }

        $this->storage->write($this->linksFile, $remaining);

        $clicks = $this->allClicks();
        $filteredClicks = array_values(array_filter($clicks, fn (array $click): bool => ($click['short_code'] ?? '') !== $code));
        $this->storage->write($this->clicksFile, $filteredClicks);

        return true;
    }

    public function recordClick(string $code, array $payload): void
    {
        $clicks = $this->allClicks();
        $clicks[] = $payload;
        $this->storage->write($this->clicksFile, $clicks);

        $this->updateByCode($code, function (array $link): array {
            $link['clicks'] = (int) ($link['clicks'] ?? 0) + 1;
            $link['last_clicked_at'] = (new DateTimeImmutable())->format(DateTimeInterface::ATOM);
            return $link;
        });
    }
}
