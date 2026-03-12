<?php

declare(strict_types=1);

final class ShortCodeGenerator
{
    private const ALPHABET = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    public function __construct(private LinkRepository $repository)
    {
    }

    public function generate(int $length = 6): string
    {
        $alphabet = self::ALPHABET;
        $maxIndex = strlen($alphabet) - 1;

        do {
            $code = '';

            for ($i = 0; $i < $length; $i++) {
                $code .= $alphabet[random_int(0, $maxIndex)];
            }
        } while ($this->repository->codeExists($code));

        return $code;
    }

    public function sanitizeAlias(string $alias): string
    {
        $cleaned = preg_replace('/[^a-zA-Z0-9_-]+/', '', trim($alias)) ?: '';
        return substr($cleaned, 0, 40);
    }
}
