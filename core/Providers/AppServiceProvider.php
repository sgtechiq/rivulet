<?php

namespace Rivulet\Providers;

class AppServiceProvider extends ServiceProvider {
    public function register() {
        // Register custom services
        $servicesDir = $this->app->basePath('app/Services');
        if (is_dir($servicesDir)) {
            $files = glob($servicesDir . '/*.php');
            foreach ($files as $file) {
                $class = 'App\\Services\\' . basename($file, '.php');
                if (class_exists($class)) {
                    $service = new $class($this->app);
                    if (method_exists($service, 'register')) {
                        $service->register();
                    }
                }
            }
        }
    }

    public function boot() {
        // Boot custom services
        $servicesDir = $this->app->basePath('app/Services');
        if (is_dir($servicesDir)) {
            $files = glob($servicesDir . '/*.php');
            foreach ($files as $file) {
                $class = 'App\\Services\\' . basename($file, '.php');
                if (class_exists($class)) {
                    $service = new $class($this->app);
                    if (method_exists($service, 'boot')) {
                        $service->boot();
                    }
                }
            }
        }

        // Auto-load custom helpers (require files to define functions)
        $helpersDir = $this->app->basePath('app/Helpers');
        if (is_dir($helpersDir)) {
            $files = glob($helpersDir . '/*.php');
            foreach ($files as $file) {
                require_once $file;
            }
        }
    }
}