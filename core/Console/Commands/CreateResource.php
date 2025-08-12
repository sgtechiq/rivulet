<?php
namespace Rivulet\Console\Commands;

use Exception;
use Rivulet\Rivulet;

/**
 * Command to generate new resource migration files
 *
 * Creates database migration files for new resources with proper table structure.
 * Includes both up() and down() methods for migration and rollback.
 */
class CreateResource
{
    /**
     * @var Rivulet The application instance
     */
    protected $app;

    /**
     * Constructor
     *
     * @param Rivulet $app The application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    /**
     * Executes the resource creation command
     *
     * @param array $args Command arguments [resource_name]
     * @return void
     * @throws Exception If resource name is not provided
     */
    public function execute(array $args = [])
    {
        if (empty($args[0])) {
            throw new Exception("Resource name is required");
        }

        $name    = $args[0];
        $path    = $this->getMigrationPath($name);
        $content = $this->generateMigrationContent($name);

        file_put_contents($path, $content);
        echo "Resource migration created: {$path}\n";
    }

    /**
     * Generates the migration file path
     *
     * @param string $name Resource name
     * @return string Full path to migration file
     */
    protected function getMigrationPath(string $name): string
    {
        $dir       = $this->app->basePath('database/Migrations');
        $timestamp = date('Y_m_d_His');
        $fileName  = "{$timestamp}_create_" . strtolower($name) . "_table";
        return "{$dir}/{$fileName}.php";
    }

    /**
     * Generates the migration class content
     *
     * @param string $name Resource name
     * @return string Generated PHP code
     */
    protected function generateMigrationContent(string $name): string
    {
        $className = 'Create' . ucfirst($name) . 'Table';
        $tableName = strtolower($name);

        return <<<PHP
<?php

use Rivulet\\Database\\Migrations\\Migration;

class {$className} extends Migration
{
    /**
     * Run the migrations
     */
    public function up()
    {
        \$this->executeSchema(function (\$builder) {
            \$builder->createTable('{$tableName}', function (\$add) {
                \$add->column('id', 'INT', [
                    'auto_increment' => true,
                    'primary_key' => true
                ]);
                \$add->column('created_at', 'TIMESTAMP', [
                    'default' => 'CURRENT_TIMESTAMP'
                ]);
                \$add->column('updated_at', 'TIMESTAMP', [
                    'default' => 'CURRENT_TIMESTAMP',
                    'on_update' => 'CURRENT_TIMESTAMP'
                ]);

                // Add your columns here:
                // \$add->column('name', 'VARCHAR', ['length' => 255]);
                // \$add->column('description', 'TEXT');
                // \$add->column('is_active', 'BOOLEAN');
            });
        });
    }

    /**
     * Reverse the migrations
     */
    public function down()
    {
        \$this->executeSchema(function (\$builder) {
            \$builder->dropTable('{$tableName}');
        });
    }
}
PHP;
    }
}
