<?php
namespace Rivulet\Console\Commands;

use Exception;
use Rivulet\Rivulet;

/**
 * Command to generate new view template files
 *
 * Creates HTML template files in the views directory with basic structure.
 * Supports nested directory structures for organized views.
 */
class CreateTemplate
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
     * Executes the template creation command
     *
     * @param array $args Command arguments [template_name]
     * @return void
     * @throws Exception If template name is not provided
     */
    public function execute(array $args = [])
    {
        if (empty($args[0])) {
            throw new Exception("Template name is required");
        }

        $name    = $args[0];
        $dir     = $this->app->basePath('resources/views');
        $path    = $this->resolvePath($dir, $name, 'html');
        $content = $this->generateTemplateContent($name);

        file_put_contents($path, $content);
        echo "Template created: {$path}\n";
    }

    /**
     * Generates the template content
     *
     * @param string $name Template name
     * @return string Generated HTML content
     */
    protected function generateTemplateContent(string $name): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$name}</title>
    <!-- Add your CSS links here -->
    <!-- <link rel="stylesheet" href="/css/app.css"> -->
</head>
<body>
    <div class="container">
        <h1>{$name} Template</h1>

        <!-- Add your template content here -->

        <!-- Example section: -->
        <!-- <section>
            <p>Welcome to your new template</p>
        </section> -->
    </div>

    <!-- Add your JavaScript scripts here -->
    <!-- <script src="/js/app.js"></script> -->
</body>
</html>
HTML;
    }

    /**
     * Resolves the full file path including directory structure
     *
     * @param string $baseDir Base directory path
     * @param string $name Template name (may include subdirectories)
     * @param string $ext File extension
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
