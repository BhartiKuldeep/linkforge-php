<?php

declare(strict_types=1);

final class JsonStorage
{
    public function read(string $file): array
    {
        if (!file_exists($file)) {
            return [];
        }

        $contents = file_get_contents($file);
        if ($contents === false || trim($contents) === '') {
            return [];
        }

        $decoded = json_decode($contents, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function write(string $file, array $data): void
    {
        $directory = dirname($file);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $handle = fopen($file, 'c+');
        if ($handle === false) {
            throw new RuntimeException('Unable to open storage file.');
        }

        try {
            if (!flock($handle, LOCK_EX)) {
                throw new RuntimeException('Unable to lock storage file.');
            }

            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            fflush($handle);
            flock($handle, LOCK_UN);
        } finally {
            fclose($handle);
        }
    }
}
