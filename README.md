# ✨ Rivulet API – Modern PHP API Framework (v1.0.0+)



**Rivulet** is a **fast, lightweight, and production-ready PHP framework** designed exclusively for **API development**. It focuses on **simplicity, performance, security, and extensibility**, making it ideal for building scalable and maintainable APIs. Built for **PHP 8.3+**, Rivulet can be deployed on **shared hosting, VPS, Docker, or cloud environments**.

No unnecessary web view bloat — only **what you need for APIs**.

---

## 🎯 Core Goals

- ⚡ **Fast & Lean**: Minimal overhead, optimized for performance.
- 🧰 **API-First**: Purely API-focused, no unnecessary web view code.
- 🛠️ **Extensible & Modular**: Easily add services, helpers, middleware, and custom packages.
- 🔐 **Secure by Default**: Token-based authentication, robust input validation.
- 🚀 **Production-Ready**: Works out-of-the-box on shared hosting, Docker, VPS, or cloud.
- 🧠 **CLI-Powered Development**: `php luna` CLI makes scaffolding, migrations, and tasks effortless.
- ⚙️ **Minimal Setup**: Ready to go after installation with `.env` configuration.

---

## 🏗️ Features Overview

### 📦 Composer & Environment

- Base **Composer.json** with all required packages, version, author, and repository.
- `.env` and `.env.example` for easy configuration of **database, mail, notifications, and other services**.

### 🛢️ Database & ORM

- Lightweight ORM inspired by CodeIgniter and Laravel.
- **CRUD operations**: insert, update, delete, select, limit, orderBy, groupBy, relations.
- **Relations supported**: hasOne, hasMany, belongsTo, belongsToMany.
- **Migrations & Seeders**: Create, alter, drop tables; populate initial data.
- Supports **MySQL, MariaDB, SQLite, PostgreSQL**.
- Multiple connections per project (all same driver).

### 🧩 Base Model & Controller

- Models define **table name, connection, fillables, guarded, hidden columns**.
- Controllers extend a **BaseController** with built-in **Request & Response handling**.
- JSON response helpers and automatic request validation available out-of-the-box.

### 🛣️ Routing System

- **File-based routing** (`routes/api.php`, `routes/adminapi.php`).
- Supports **prefixes, groups, middleware, and route collections**.
- Example:

```php
prefix('user', function() {
    route('GET', '/', UserController::class, 'list');
    route('POST', '/create', UserController::class, 'store');
});
```

- **File viewer routes** to serve static files if needed.

### 🔧 Service Providers & Helpers

- **AppServiceProvider** for registering routes, services, and middleware.
- Global **helpers** auto-loaded and usable anywhere in controllers, models, or services.
- Add custom services in `app/Services` or helpers in `app/Helpers` without manual includes.

### 🔑 Authentication & Middleware

- **Token-based API security** implemented from scratch.
- Password hashing and verification helpers.
- Middleware for protected routes, logging, and other requirements.

### 📂 File System

- Upload, delete, copy, move, rename, download files & directories.
- **Zip compression/extraction** for directories.
- Storage linked to public URL via CLI for browser access (`url/storage/file`).

### 📑 Template Engine

- Lightweight `.html` templating system.
- Supports **single variables, arrays, loops/mapping**.
- Default pages for **404**, **Unauthorized**, and general **welcome**.
- Load templates by **name or subdirectory** (`dashboard.home`).

### ✉️ Mailing System

- Supports **SMTP, Mailgun, SendGrid, PHP Mail, Sendmail**.
- Send **text, HTML, or templates**.
- Attachments supported.
- Multiple mailers configurable via `.env`.

### 🔔 Notifications

- Modular notifications: **Firebase, Pusher, Slack, WhatsApp, SMS, Mail**.
- Env-based enablement for easy configuration.

### 📜 Logging, Validation & Cache

- Daily or monthly logs in `storage/logs`.
- File-based caching with TTL in `storage/cache`.
- Advanced validator with **rules**: required, number, email, regex, min, max, custom rules.

### ⏰ Queues & Task Scheduling

- Database or Redis-backed **job queues** with retry and failure support.
- Task scheduling via CLI and configuration.

### 🌍 HTTP Client

- Simple **curl-based client** for external API requests (GET, POST, PUT, DELETE).
- Supports **JSON, form data, authentication headers**.

### 📅 Events & Listeners

- Fire **events** and handle via listeners.
- Register in `app/Events` and configure in `config/events.php`.

### 🗝️ Session & Cookies

- Secure **session & cookie managers**: set/get/forget/flash.
- Configurable parameters: secure, httpOnly, path, domain.

### 💻 CLI Tool – `luna`

Powerful commands to speed up development:

- **Create scaffolds:**

```bash
php luna create:model User
php luna create:controller UserController
php luna create:service UserService
php luna create:template dashboard.home
php luna create:event UserRegistered
php luna create:rule Number
php luna create:resource Users
php luna create:seed AdminUser
```

- **Database operations:** migrate, seed, rollback.
- **Cache & Logs:** clear, cache, list.
- **Server:** run development server (`php luna run localhost:8000`).
- **Other:** generate app key, optimize, route listing, queue/schedule management.

### 🧪 Default Setup

- Default `users` table migration & seeder:

```text
id | name | email | phone | username | password | authtoken | created_at | updated_at | deleted_at | deleted
```

- Default user: `Administrator / email@domain.com / username: admin / password: admin`
- Default **UserController** with standard CRUD operations: list, show, store, edit, delete, destroy.

### 🚀 Deployment

- **Dockerfile** for containerized deployment.
- `.htaccess` for Apache/shared hosting.
- `.env.production` template for production.
- Compatible with optional **Carbon** (dates).

### 📚 Documentation & Tutorials

Full tutorials provided in [docs](https://rivuletapi.netlify.app/) covering:

- Database connection, migrations, seeders.
- Model & controller creation.
- Routing, middleware, authentication.
- File operations, template usage.
- Mail & notifications.
- Logging, caching, validation.
- Queues, events, CLI usage, testing, deployment.

### 🤝 Contributing

- Fork the repository → create a branch → PR.
- Issues and suggestions welcome at [GitHub](https://github.com/sgtechiq/rivulet).

### 📜 License

**MIT License** – see the [LICENSE](LICENSE) file for details.

---

✅ **Rivulet API**: The PHP API framework that’s **fast, lean, and developer-friendly**, giving you everything you need to **build scalable APIs effortlessly**.

