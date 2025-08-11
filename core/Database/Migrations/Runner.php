<?php

namespace Rivulet\Database\Migrations;

use Rivulet\Rivulet;
use PDO;

class Runner {
    protected $app;
    protected $pdo;
    protected $migrationsTable;

    public function __construct(Rivulet $app) {
        $this->app = $app;
        $this->pdo = \Rivulet\Database\Connection::get();
        $this->migrationsTable = config('database.migrations', 'migrations');
        $this->createMigrationsTable();
    }

    protected function createMigrationsTable() {
        $query = "CREATE TABLE IF NOT EXISTS {$this->migrationsTable} (id INT AUTO_INCREMENT PRIMARY KEY, migration VARCHAR(255), batch INT)";
        $this->pdo->exec($query);
    }

    public function migrate() {
        $migrationsDir = $this->app->basePath('database/Migrations');
        $files = glob($migrationsDir . '/*.php');
        $batch = $this->getNextBatch();

        foreach ($files as $file) {
            $class = basename($file, '.php');
            if ($this->isMigrated($class)) {
                continue;
            }
            require $file;
            $instance = new $class();
            $instance->up();
            $this->recordMigration($class, $batch);
        }
    }

    public function seed() {
        $seedersDir = $this->app->basePath('database/Seeders');
        $files = glob($seedersDir . '/*.php');
        foreach ($files as $file) {
            require $file;
            $class = basename($file, '.php');
            $instance = new $class();
            $instance->run();
        }
    }

    public function rollback() {
        $lastBatch = $this->getLastBatch();
        if ($lastBatch === 0) {
            echo "No migrations to rollback\n";
            return;
        }

        $stmt = $this->pdo->prepare("SELECT * FROM {$this->migrationsTable} WHERE batch = ? ORDER BY id DESC");
        $stmt->execute([$lastBatch]);
        $migrations = $stmt->fetchAll();

        foreach ($migrations as $migration) {
            $class = $migration['migration'];
            $file = $this->app->basePath('database/Migrations') . '/' . $class . '.php';
            if (!file_exists($file)) {
                echo "Migration file not found: {$class}\n";
                continue;
            }
            require $file;
            $instance = new $class();
            $instance->down();
            echo "Rolled back: {$class}\n";

            $deleteStmt = $this->pdo->prepare("DELETE FROM {$this->migrationsTable} WHERE id = ?");
            $deleteStmt->execute([$migration['id']]);
        }
    }

    protected function getNextBatch() {
        $stmt = $this->pdo->query("SELECT MAX(batch) FROM {$this->migrationsTable}");
        return ($stmt->fetchColumn() ?? 0) + 1;
    }

    protected function isMigrated($migration) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM {$this->migrationsTable} WHERE migration = ?");
        $stmt->execute([$migration]);
        return $stmt->fetchColumn() > 0;
    }

    protected function recordMigration($migration, $batch) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->migrationsTable} (migration, batch) VALUES (?, ?)");
        $stmt->execute([$migration, $batch]);
    }

    protected function getLastBatch() {
        $stmt = $this->pdo->query("SELECT MAX(batch) FROM {$this->migrationsTable}");
        return $stmt->fetchColumn() ?? 0;
    }
}