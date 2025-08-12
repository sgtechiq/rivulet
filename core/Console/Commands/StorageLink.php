<?php
namespace Rivulet\Console\Commands;

use Rivulet\Rivulet;

class StorageLink
{
    /**
     * Rivulet application instance
     * @var Rivulet
     */
    protected $app;

    /**
     * Create new storage link command instance
     * @param Rivulet $app Application instance
     */
    public function __construct(Rivulet $app)
    {
        $this->app = $app;
    }

    /**
     * Execute storage linking command
     * Creates a symbolic link from public/storage to storage/uploads
     * @param array $args Command arguments (unused)
     */
    public function execute($args)
    {
        $target = $this->app->basePath('storage/uploads');
        $link   = $this->app->basePath('public/storage');

        if (is_link($link)) {
            unlink($link);
        }

        symlink($target, $link);
        echo "Storage linked [{$target} -> {$link}]\n";
    }
}
