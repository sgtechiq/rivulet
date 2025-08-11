#!/bin/bash

# Script to set up Rivulet framework directory structure and base files
# Assumes 'rivulet' directory exists in the current working directory

set -e # Exit on error

# Function to create directory with permissions
create_dir() {
    mkdir -p "$1"
    chmod 755 "$1"
    echo "Created directory: $1"
}

# Function to create empty PHP file with permissions
create_php_file() {
    echo "<?php" > "$1"
    chmod 644 "$1"
    echo "Created PHP file: $1"
}

# Function to create empty file with permissions
create_empty_file() {
    touch "$1"
    chmod 644 "$1"
    echo "Created file: $1"
}

# Create directories
create_dir "app/Controllers"
create_dir "app/Models"
create_dir "app/Services"
create_dir "app/Helpers"
create_dir "app/Rules"
create_dir "app/Events"
create_dir "bootstrap"
create_dir "config"
create_dir "core/Auth"
create_dir "core/Cache"
create_dir "core/Console/Commands"
create_dir "core/Database/Relations"
create_dir "core/Database/Operations"
create_dir "core/Database/Migrations"
create_dir "core/Http"
create_dir "core/Logging"
create_dir "core/Filesystem"
create_dir "core/Middleware"
create_dir "core/Providers"
create_dir "core/Routing"
create_dir "core/Support"
create_dir "core/Validation/Rules"
create_dir "core/Views"
create_dir "database/Migrations"
create_dir "database/Seeders"
create_dir "docs"
create_dir "public"
create_dir "resources/css"
create_dir "resources/js"
create_dir "resources/images"
create_dir "resources/views"
create_dir "routes"
create_dir "storage/logs"
create_dir "storage/cache"
create_dir "storage/uploads"
create_dir "tests"

# Create PHP files
create_php_file "bootstrap/app.php"
create_php_file "config/app.php"
create_php_file "config/auth.php"
create_php_file "config/database.php"
create_php_file "config/filesystems.php"
create_php_file "config/logging.php"
create_php_file "config/mail.php"
create_php_file "config/routes.php"
create_php_file "config/services.php"
create_php_file "config/views.php"
create_php_file "core/Auth/Authentication.php"
create_php_file "core/Cache/Cache.php"
create_php_file "core/Console/Console.php"
create_php_file "core/Http/Kernel.php"
create_php_file "core/Http/Request.php"
create_php_file "core/Http/Response.php"
create_php_file "core/Logging/Logs.php"
create_php_file "core/Providers/AppServiceProvider.php"
create_php_file "core/Controller.php"
create_php_file "core/Model.php"
create_php_file "core/Rivulet.php"
create_php_file "routes/api.php"

# Create .htaccess with basic content
cat > public/.htaccess << EOL
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [L]
</IfModule>
EOL
chmod 644 public/.htaccess
echo "Created file: public/.htaccess"

# Create index.php
create_php_file "public/index.php"

# Create .env and .env.example
touch .env .env.example
chmod 644 .env .env.example
echo "Created file: .env"
echo "Created file: .env.example"

# Create luna CLI script
cat > luna << EOL
#!/usr/bin/env php
<?php
// Rivulet CLI entry point
EOL
chmod 755 luna
echo "Created executable: luna"

# Create composer.json stub
cat > composer.json << EOL
{
    "name": "rivulet",
    "description": "A lightweight PHP API framework",
    "require": {
        "php": "^8.3"
    }
}
EOL
chmod 644 composer.json
echo "Created file: composer.json"

# Create .gitignore
cat > .gitignore << EOL
/vendor/
/.env
/storage/logs/*
/storage/cache/*
/storage/uploads/*
EOL
chmod 644 .gitignore
echo "Created file: .gitignore"

# Create README.md stub
cat > README.md << EOL
# Rivulet API Framework

A lightweight, fast, and secure PHP framework for API development.

## Installation
\`\`\`bash
composer create-project rivulet
\`\`\`

## Documentation
See the `docs/` directory for detailed usage instructions.
EOL
chmod 644 README.md
echo "Created file: README.md"

echo "Rivulet directory structure and base files created successfully!"