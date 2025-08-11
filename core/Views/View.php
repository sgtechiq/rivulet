<?php
namespace Rivulet\Views;

use Exception;
use Rivulet\Rivulet;

/**
 * View Renderer
 *
 * Handles template rendering with support for multiple view paths
 */
class View
{
    protected $app;
    protected $paths;
    protected $extension;

    /**
     * Initialize view renderer
     * @param Rivulet $app Application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app       = $app;
        $this->paths     = $app->getConfig('views.paths', []);
        $this->extension = $app->getConfig('views.extension', '.html');
    }

    /**
     * Render a template with data
     * @param string $template Template name
     * @param array $data View data
     * @throws Exception If template not found
     */
    public function render($template, $data = [])
    {
        $file = $this->resolveTemplatePath($template);
        if (! file_exists($file)) {
            throw new Exception("Template {$template} not found");
        }
        $content = file_get_contents($file);
        $engine  = new Engine($content, $data);
        return $engine->parse();
    }

    /**
     * Resolve template file path
     * @param string $template Template name
     * @throws Exception If template path cannot be resolved
     */
    protected function resolveTemplatePath($template)
    {
        $template = str_replace('.', '/', $template) . $this->extension;
        foreach ($this->paths as $path) {
            $fullPath = $path . '/' . $template;
            if (file_exists($fullPath)) {
                return $fullPath;
            }
        }
        throw new Exception("Template path not resolved");
    }
}
