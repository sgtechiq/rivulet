<?php
namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class KeyGenerate
{
    /**
     * The Rivulet application instance
     *
     * @var Rivulet
     */
    protected $app;

    /**
     * Create a new command instance
     *
     * @param Rivulet $app The application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    /**
     * Execute the command to generate and set application key
     *
     * Generates a secure 32-character random key and updates the .env file.
     * Displays the generated key to the console.
     *
     * @param array $args Command arguments (unused in this command)
     * @return void
     */
    public function execute($args)
    {
        $key     = bin2hex(random_bytes(16)); // Generate 32-character key
        $envFile = $this->app->basePath('.env');

        // Update .env file with new key
        $content = file_get_contents($envFile);
        $content = preg_replace('/^APP_KEY=.*$/m', "APP_KEY={$key}", $content);
        file_put_contents($envFile, $content);

        echo "APP_KEY generated: {$key}\n";
    }
}
