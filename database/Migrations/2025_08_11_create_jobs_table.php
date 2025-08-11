<?php

use Rivulet\Database\Migrations\Migration;

class CreateJobsTable extends Migration {
    public function up() {
        $this->executeSchema(function ($builder) {
            $builder->createTable('jobs', function ($add) {
                $add('id', 'BIGINT', ['auto_increment' => true, 'primary_key' => true]);
                $add('queue', 'VARCHAR(255)', ['nullable' => false]);
                $add('payload', 'LONGTEXT', ['nullable' => false]);
                $add('attempts', 'TINYINT UNSIGNED', ['default' => '0']);
                $add('reserved_at', 'INT UNSIGNED', ['nullable' => true]);
                $add('available_at', 'INT UNSIGNED', ['nullable' => false]);
                $add('created_at', 'INT UNSIGNED', ['nullable' => false]);
            });
        });
    }

    public function down() {
        $this->executeSchema(function ($builder) {
            $builder->dropTable('jobs');
        });
    }
}