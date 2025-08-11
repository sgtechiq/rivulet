<?php

namespace Rivulet\Views;

use Rivulet\Rivulet;
use Exception;

class View {
    protected $app;
    protected $paths;
    protected $extension;

    public function __construct(Rivulet $app) {
        $this->app = $app;
        $this->paths = $app->getConfig('views.paths', []);
        $this->extension = $app->getConfig('views.extension', '.html');
    }

    public function render($template, $data = []) {
        $file = $this->resolveTemplatePath($template);
        if (!file_exists($file)) {
            throw new Exception("Template {$template} not found");
        }
        $content = file_get_contents($file);
        $engine = new Engine($content, $data);
        return $engine->parse();
    }

    protected function resolveTemplatePath($template) {
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