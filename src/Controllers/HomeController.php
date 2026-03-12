<?php

declare(strict_types=1);

final class HomeController
{
    public function __construct(
        private LinkService $service,
        private View $view,
        private Flash $flash,
    ) {
    }

    public function index(): void
    {
        $dashboard = $this->service->dashboard();

        $this->view->render('home', [
            'title' => 'Modern PHP URL Shortener',
            'dashboard' => $dashboard,
            'flashMessages' => $this->flash->all(),
            'csrfToken' => csrf_token(),
        ]);
    }

    public function store(): never
    {
        if (!verify_csrf($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Invalid CSRF token.';
            exit;
        }

        try {
            $link = $this->service->create($_POST);
            $this->flash->add('success', 'Short link created: ' . app_url($link['short_code']));
        } catch (InvalidArgumentException $exception) {
            $this->flash->add('error', $exception->getMessage());
        } catch (Throwable) {
            $this->flash->add('error', 'Something went wrong while creating your short link.');
        }

        redirect_to('/');
    }
}
