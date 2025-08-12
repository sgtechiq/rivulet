<?php

use Rivulet\Database\Migrations\Migration;

class CreateAuthorsArticles extends Migration
{
    public function up()
    {
        $this->executeSchema(function ($builder) {
            $builder->createTable('authors', function ($add) {
                $add('id', 'INT', ['auto_increment' => true, 'primary_key' => true]);
                $add('name', 'VARCHAR(255)', ['nullable' => false]);
                $add('email', 'VARCHAR(255)', ['nullable' => false]);
                $add('password', 'VARCHAR(255)', ['nullable' => false]);
                $add('created_at', 'TIMESTAMP', ['default' => 'CURRENT_TIMESTAMP']);
                $add('updated_at', 'TIMESTAMP', ['default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']);
            });

            $builder->createTable('articles', function ($add) {
                $add('id', 'INT', ['auto_increment' => true, 'primary_key' => true]);
                $add('title', 'VARCHAR(255)', ['nullable' => false]);
                $add('slug', 'VARCHAR(255)', ['nullable' => false]);
                $add('content', 'TEXT', ['nullable' => false]);
                $add('author_id', 'INT', ['nullable' => false]);
                $add('created_at', 'TIMESTAMP', ['default' => 'CURRENT_TIMESTAMP']);
                $add('updated_at', 'TIMESTAMP', ['default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']);
            });
        });
    }

    public function down()
    {
        $this->executeSchema(function ($builder) {
            $builder->dropTable('articles');
            $builder->dropTable('authors');
        });
    }
}
