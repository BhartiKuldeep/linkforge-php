# LinkForge PHP

A modern, responsive URL shortener built with plain PHP. LinkForge lets you create short URLs, use custom aliases, set expiration dates, track clicks, and manage everything from a clean dashboard — all without setting up a database.

## Repository

**Name:** `linkforge-php`  
**Short description:** Responsive PHP URL shortener with custom aliases, expiration dates, click analytics, and zero-database setup.

## Features

- Create short URLs from long links
- Optional custom alias support
- Optional expiration date for each short link
- Automatic click tracking
- Recent activity panel with IP, referrer, and device info
- Delete links from the dashboard
- Responsive UI for desktop, tablet, and mobile
- CSRF protection on create and delete actions
- JSON-based file storage for quick local setup
- Clean routing with the built-in PHP server

## Tech Stack

- PHP 8+
- HTML5
- CSS3
- Vanilla JavaScript
- JSON file storage

## Why This Project Is Good for GitHub

This is not a single-file beginner demo. It is structured like a real project with controllers, services, repositories, templates, storage, and reusable helpers. It is easy to clone, easy to run, and good for showcasing practical PHP skills on your GitHub profile.

## Project Structure

```text
linkforge-php/
├── bootstrap.php
├── router.php
├── config/
│   └── app.php
├── public/
│   ├── index.php
│   └── assets/
│       ├── css/style.css
│       └── js/app.js
├── src/
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   ├── RedirectController.php
│   │   └── DeleteController.php
│   ├── Repositories/
│   │   └── LinkRepository.php
│   ├── Services/
│   │   ├── LinkService.php
│   │   ├── ShortCodeGenerator.php
│   │   └── UrlValidator.php
│   ├── Storage/
│   │   └── JsonStorage.php
│   └── Support/
│       ├── helpers.php
│       ├── Flash.php
│       └── View.php
├── templates/
│   ├── home.php
│   ├── layouts/
│   │   └── app.php
│   └── partials/
│       └── flash.php
├── storage/
│   ├── links.json
│   └── clicks.json
├── LICENSE
└── README.md
```

## How It Works

1. The user enters a long URL.
2. The app validates and normalizes the URL.
3. A unique short code is generated, or a custom alias is used.
4. The record is stored in `storage/links.json`.
5. When the short link is visited, a click log is stored in `storage/clicks.json`.
6. The visitor is redirected to the original URL.

## Requirements

- PHP 8.0 or higher
- No database required
- No Composer packages required

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/BhartiKuldeep/linkforge-php.git
cd linkforge-php
```

### 2. Start the local development server

```bash
php -S localhost:8000 router.php
```

### 3. Open the project in your browser

```text
http://localhost:8000
```

## Usage

### Create a short link

- Enter a long URL
- Optionally add a custom alias
- Optionally select an expiration date
- Click **Create short URL**

### Visit a short link

Open a generated short URL like:

```text
http://localhost:8000/my-link
```

The app will track the click and redirect the user to the original URL.

### Delete a short link

Use the **Delete** button in the dashboard to remove any saved short link.

## Data Storage

This project uses JSON files instead of MySQL so it works immediately after cloning.

- `storage/links.json` stores all shortened links
- `storage/clicks.json` stores click history

To reset the app data locally, you can clear those files and replace their contents with:

```json
[]
```

## Security Notes

- CSRF token validation is used for form submissions
- Input is validated before saving
- Custom aliases are sanitized before use
- Output is escaped in templates to reduce XSS risk

## Future Improvements

- User authentication
- Admin dashboard with search and filters
- QR code generation
- REST API for programmatic link creation
- Export analytics as CSV
- Docker setup
- MySQL version for production-style deployment
- Password-protected short links

## License

This project is licensed under the MIT License.
