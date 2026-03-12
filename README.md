# LinkForge

A modern, responsive URL shortener built with plain PHP. It ships with clean routing, custom aliases, expiration dates, click analytics, and file-based JSON storage so it runs without setting up a database.

## Short description

Responsive PHP URL shortener with custom aliases, expiration dates, analytics dashboard, and zero-database setup.

## Why this project is good for GitHub

- Looks like a serious multi-file project, not a beginner single-page demo
- Runs locally in seconds with plain PHP
- Includes routing, storage, validation, session flash messages, analytics, and a polished UI
- Easy for recruiters or friends to clone and test

## Features

- Create short URLs from long links
- Optional custom alias support
- Optional expiration date per link
- Click counting and recent click activity
- Delete short links from dashboard
- Responsive modern dashboard UI
- JSON-based persistence for zero database setup
- CSRF protection for create and delete forms
- Built-in router for `php -S`

## Tech stack

- PHP 8+
- HTML5
- CSS3
- Vanilla JavaScript
- JSON file storage

## Project structure

```text
linkforge-php/
├── bootstrap.php
├── config/
│   └── app.php
├── public/
│   ├── index.php
│   └── assets/
│       ├── css/style.css
│       └── js/app.js
├── src/
│   ├── Controllers/
│   ├── Repositories/
│   ├── Services/
│   ├── Storage/
│   └── Support/
├── storage/
│   ├── links.json
│   └── clicks.json
├── templates/
│   ├── layouts/app.php
│   ├── partials/flash.php
│   └── home.php
├── router.php
├── LICENSE
└── README.md
```

## How to run locally

### 1. Clone the repository

```bash
git clone https://github.com/BhartiKuldeep/linkforge-php.git
cd linkforge-php
```

### 2. Start the PHP development server

```bash
php -S localhost:8000 router.php
```

### 3. Open in browser

```text
http://localhost:8000
```

## How it works

1. The user submits a long URL.
2. The app validates and normalizes the URL.
3. It creates a random short code or uses the custom alias.
4. The mapping is stored in `storage/links.json`.
5. When the short URL is opened, the app logs the click in `storage/clicks.json`.
6. The visitor is redirected to the original URL.

## License

MIT
