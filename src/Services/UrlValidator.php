<?php

declare(strict_types=1);

final class UrlValidator
{
    public function normalize(string $url): string
    {
        $url = trim($url);

        if ($url !== '' && !preg_match('/^https?:\/\//i', $url)) {
            $url = 'https://' . $url;
        }

        return $url;
    }

    public function isValid(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);
        return in_array(strtolower((string) $scheme), ['http', 'https'], true);
    }
}
