# Rivulet API Framework

![Logo](logo.png)

Rivulet is a modern, fast, lightweight PHP framework designed exclusively for API development. It prioritizes simplicity, performance, security, and extensibility, making it ideal for building scalable APIs. Built for PHP 8.3+, Rivulet is production-ready and deployable on shared hosting, VPS, Docker, or cloud environments. It focuses on API features, with no unnecessary web view bloat.

## Key Features

- **API-Centric Routing**: File-based routing with prefixes, groups, middleware, and file viewers. Supports caching for faster loads.
- **Lightweight ORM**: ORM with CRUD operations, relationships (hasOne, hasMany, belongsTo, belongsToMany), migrations, and seeders. Supports MySQL, MariaDB, SQLite, PostgreSQL, with multiple connections.
- **Authentication**: Token-based security (JWT-like) from scratch, with password encryption. Supports stateless or DB-stored tokens for revocation.
- **Filesystem Management**: Upload, delete, copy, move, rename, download files/directories. Zip compression/extraction. CLI-linked public storage.
- **Template Engine**: Simple .html templates for variables, array mapping/loops. Load by name or subdir. Default pages for 404/unauthorized.
- **Mailing System**: Supports SMTP, Mailgun, SendGrid, PHP mail, Sendmail. Attachments, HTML/text, or view templates. Multiple mailers.
- **Notifications**: Modular for Firebase, Pusher, Slack, WhatsApp, SMS (Twilio), and Mail. Env-based enablement.
- **Logging, Caching, Validation**: Daily/monthly logs, file-based caching with TTL. Advanced validation with rules (required, number, email, max, min, etc.) and custom rules in app/Rules.
- **Queues and Scheduling**: DB/Redis queues with jobs, retries, failed jobs table. Task scheduling via config and CLI.
- **HTTP Client**: Curl-based client for external APIs (GET/POST/PUT/DELETE, JSON/form, auth).
- **Events and Listeners**: Fire events with data, handle via listeners registered in config/events.
- **Session and Cookies**: Secure session/cookie managers with set/get/forget/flash. Configurable params (secure, httponly).
- **CLI Tool (luna)**: Comprehensive commands for creating models/controllers/services/templates/events/rules/resources/seeders, migrating/seeding/rolling back DB, clearing/caching configs/routes/logs/cache, running queue/schedule/server, generating keys, optimizing, and more.
- **Deployment Support**: Dockerfile for containerization, .htaccess for Apache/shared hosting, .env.production template. Compatible with Carbon (dates) and Geocoder (optional, user-configured).
- **Extensibility**: Modular service providers, custom helpers/services auto-loaded, custom validation rules/events/listeners/jobs.

## Installation

1. Install via Composer:
   ```
   composer create-project rivulet/rivulet yourapp
   ```
2. Navigate to the project:
   ```
   cd yourapp
   ```
3. Copy example env and configure (DB, mail, etc.):
   ```
   cp .env.example .env
   ```
4. Generate app key:
   ```
   php luna key:generate
   ```
5. Run migrations and seeders:
   ```
   php luna database:migrate
   php luna database:seed
   ```
6. Start development server:
   ```
   php luna run localhost:8000
   ```
7. For production: Use .env.production, deploy with Docker (`docker build -t rivulet . && docker run -p 80:80 rivulet`), or upload to shared hosting (enable mod_rewrite for .htaccess).

## Usage

- **Routing**: Define in routes/api.php, e.g., route('GET', '/', function() { return ['welcome']; });.
- **Models/ORM**: Extend Model, use static all/find/where, instance save/delete.
- **Controllers**: Extend Controller, use $this->request, $this->validate, etc.
- **CLI**: php luna --help for commands.
- Full tutorials in [docs/](docs/) folder, covering all features with examples.

## Contributing

Fork the repo, create branch, PR. Issues welcome at https://github.com/sgtechiq/rivulet.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
