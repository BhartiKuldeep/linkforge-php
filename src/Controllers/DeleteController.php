<?php

declare(strict_types=1);

final class DeleteController
{
    public function __construct(
        private LinkService $service,
        private Flash $flash,
    ) {
    }

    public function destroy(string $code): never
    {
        if (!verify_csrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Invalid CSRF token.';
            exit;
        }

        if ($this->service->remove($code)) {
            $this->flash->add('success', 'Short link deleted successfully.');
        } else {
            $this->flash->add('error', 'Could not find the short link you tried to delete.');
        }

        redirect_to('/');
    }
}
