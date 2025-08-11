<?php

namespace Rivulet\Database;

use PDO;
use PDOException;

class Connection {
    protected static $connections = [];

    public static function get($name = 'default'): PDO {
        if (!isset(self::$connections[$name])) {
            $config = app()->getConfig("database.connections.{$name}");
            if (!$config) {
                throw new PDOException("Database connection {$name} not configured.");
            }

            $driver = $config['driver'];
            if ($driver !== app()->getConfig('database.connections.default.driver')) {
                throw new PDOException("All connections must use the same driver: {$driver}");
            }

            $dsn = self::buildDsn($config);

            try {
                $pdo = new PDO($dsn, $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
                self::$connections[$name] = $pdo;
            } catch (PDOException $e) {
                throw new PDOException("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$connections[$name];
    }

    protected static function buildDsn($config): string {
        switch ($config['driver']) {
            case 'mysql':
            case 'mariadb':
                return "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
            case 'pgsql':
                return "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
            case 'sqlite':
                return "sqlite:{$config['database']}";
            default:
                throw new PDOException("Unsupported database driver: {$config['driver']}");
        }
    }
}