<?php

use Rivulet\Database\Migrations\Migration;

class CreateUsersTable extends Migration {
    public function up() {
        $this->executeSchema(function ($builder) {
            $builder->createTable('users', function ($add) {
                $add('id', 'INT', ['auto_increment' => true, 'primary_key' => true]);
                $add('name', 'VARCHAR(255)', ['nullable' => false]);
                $add('email', 'VARCHAR(255)', ['nullable' => false]);
                $add('phone', 'VARCHAR(255)', ['nullable' => true]);
                $add('username', 'VARCHAR(255)', ['nullable' => false]);
                $add('password', 'VARCHAR(255)', ['nullable' => false]);
                $add('authtoken', 'VARCHAR(255)', ['nullable' => true]);
                $add('created_at', 'TIMESTAMP', ['default' => 'CURRENT_TIMESTAMP']);
                $add('updated_at', 'TIMESTAMP', ['default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']);
                $add('deleted_at', 'TIMESTAMP', ['nullable' => true]);
                $add('deleted', 'TINYINT', ['default' => '0']);
            });
        });
    }

    public function down() {
        $this->executeSchema(function ($builder) {
            $builder->dropTable('users');
        });
    }
}