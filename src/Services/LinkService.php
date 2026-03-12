<?php

declare(strict_types=1);

final class LinkService
{
    public function __construct(
        private LinkRepository $repository,
        private UrlValidator $validator,
        private ShortCodeGenerator $generator,
    ) {
    }

    public function dashboard(): array
    {
        $links = $this->repository->allLinks();
        $clicks = $this->repository->allClicks();

        $activeCount = 0;
        $expiredCount = 0;
        foreach ($links as $link) {
            is_link_expired($link) ? $expiredCount++ : $activeCount++;
        }

        $topLink = null;
        foreach ($links as $link) {
            if ($topLink === null || (int) $link['clicks'] > (int) $topLink['clicks']) {
                $topLink = $link;
            }
        }

        $today = (new DateTimeImmutable())->format('Y-m-d');
        $todayClicks = 0;
        foreach ($clicks as $click) {
            if (str_starts_with((string) ($click['clicked_at'] ?? ''), $today)) {
                $todayClicks++;
            }
        }

        return [
            'links' => $links,
            'clicks' => array_slice($clicks, 0, 8),
            'stats' => [
                'total_links' => count($links),
                'active_links' => $activeCount,
                'expired_links' => $expiredCount,
                'total_clicks' => array_sum(array_map(fn (array $link): int => (int) ($link['clicks'] ?? 0), $links)),
                'today_clicks' => $todayClicks,
                'top_link' => $topLink,
            ],
        ];
    }

    public function create(array $input): array
    {
        $originalUrl = $this->validator->normalize((string) ($input['original_url'] ?? ''));
        $customAlias = trim((string) ($input['custom_alias'] ?? ''));
        $expiresAt = trim((string) ($input['expires_at'] ?? ''));

        if (!$this->validator->isValid($originalUrl)) {
            throw new InvalidArgumentException('Please enter a valid HTTP or HTTPS URL.');
        }

        $code = '';
        if ($customAlias !== '') {
            $code = $this->generator->sanitizeAlias($customAlias);
            if ($code === '') {
                throw new InvalidArgumentException('Custom alias can use only letters, numbers, underscore, and hyphen.');
            }
            if ($this->repository->codeExists($code)) {
                throw new InvalidArgumentException('That custom alias is already taken. Please choose another one.');
            }
        } else {
            $code = $this->generator->generate();
        }

        $expiryValue = null;
        if ($expiresAt !== '') {
            try {
                $expiry = new DateTimeImmutable($expiresAt);
                $now = new DateTimeImmutable('today');
                if ($expiry < $now) {
                    throw new InvalidArgumentException('Expiration date cannot be in the past.');
                }
                $expiryValue = $expiry->setTime(23, 59, 59)->format(DateTimeInterface::ATOM);
            } catch (Exception $exception) {
                if ($exception instanceof InvalidArgumentException) {
                    throw $exception;
                }
                throw new InvalidArgumentException('Expiration date is not valid.');
            }
        }

        $record = [
            'id' => bin2hex(random_bytes(12)),
            'original_url' => $originalUrl,
            'short_code' => $code,
            'clicks' => 0,
            'created_at' => (new DateTimeImmutable())->format(DateTimeInterface::ATOM),
            'expires_at' => $expiryValue,
            'last_clicked_at' => null,
        ];

        return $this->repository->create($record);
    }

    public function resolve(string $code): ?array
    {
        return $this->repository->findByCode($code);
    }

    public function remove(string $code): bool
    {
        return $this->repository->deleteByCode($code);
    }

    public function registerClick(string $code): void
    {
        $this->repository->recordClick($code, [
            'id' => bin2hex(random_bytes(12)),
            'short_code' => $code,
            'ip_address' => client_ip(),
            'referrer' => $_SERVER['HTTP_REFERER'] ?? 'Direct',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            'clicked_at' => (new DateTimeImmutable())->format(DateTimeInterface::ATOM),
        ]);
    }
}
