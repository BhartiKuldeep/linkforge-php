<?php

declare(strict_types=1);

final class Flash
{
    private const KEY = '_flash_messages';

    public function add(string $type, string $message): void
    {
        $_SESSION[self::KEY] ??= [];
        $_SESSION[self::KEY][] = [
            'type' => $type,
            'message' => $message,
        ];
    }

    public function all(): array
    {
        $messages = $_SESSION[self::KEY] ?? [];
        unset($_SESSION[self::KEY]);

        return is_array($messages) ? $messages : [];
    }
}
