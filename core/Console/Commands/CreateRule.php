<?php
namespace Rivulet\Console\Commands;

use Exception;
use Rivulet\Rivulet;

/**
 * Command to generate new validation rule classes
 *
 * Creates validation rule classes with standard passes() and message() methods.
 * Supports namespaced rules by creating appropriate directory structures.
 */
class CreateRule
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
     * Executes the rule creation command
     *
     * @param array $args Command arguments [rule_name]
     * @return void
     * @throws Exception If rule name is not provided
     */
    public function execute(array $args = [])
    {
        if (empty($args[0])) {
            throw new Exception("Rule name is required");
        }

        $name    = $args[0];
        $dir     = $this->app->basePath('app/Rules');
        $path    = $this->resolvePath($dir, $name, 'php');
        $content = $this->generateRuleContent($name);

        file_put_contents($path, $content);
        echo "Rule created: {$path}\n";
    }

    /**
     * Generates the rule class content
     *
     * @param string $name Rule name (may include namespace)
     * @return string Generated PHP code
     */
    protected function generateRuleContent(string $name): string
    {
        $className = basename($name);
        $namespace = 'App\\Rules';

        // Handle namespaced rules
        if (strpos($name, '/') !== false) {
            $namespace .= '\\' . str_replace('/', '\\', dirname($name));
        }

        return <<<PHP
<?php

namespace {$namespace};

use Rivulet\\Validation\\Rule;

class {$className} implements Rule
{
    /**
     * Determine if the validation rule passes
     *
     * @param string \$field The field being validated
     * @param mixed \$value The field value
     * @return bool
     */
    public function passes(string \$field, \$value): bool
    {
        // Implement validation logic
        return true;
    }

    /**
     * Get the validation error message
     *
     * @param string \$field The field being validated
     * @return string
     */
    public function message(string \$field): string
    {
        return "The {\$field} field failed validation";
    }
}
PHP;
    }

    /**
     * Resolves the full file path including directory structure
     *
     * @param string \$baseDir Base directory path
     * @param string \$name Rule name (may include subdirectories)
     * @param string \$ext File extension
     * @return string Full file path
     */
    protected function resolvePath(string $baseDir, string $name, string $ext): string
    {
        if (strpos($name, '/') !== false) {
            $parts  = explode('/', $name);
            $file   = array_pop($parts);
            $subDir = implode('/', $parts);
            $dir    = $baseDir . '/' . $subDir;

            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            return $dir . '/' . $file . '.' . $ext;
        }

        return $baseDir . '/' . $name . '.' . $ext;
    }
}
