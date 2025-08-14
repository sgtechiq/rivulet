<?php
namespace Rivulet\Filesystem;

use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Rivulet\Http\Response;
use Rivulet\Rivulet;
use ZipArchive;

/**
 * Filesystem Manager
 *
 * Provides comprehensive file system operations including:
 * - File uploads/downloads
 * - Directory management
 * - File operations (copy/move/rename/delete)
 * - Zip archive support
 */
class Filesystem
{
    /**
     * @var Rivulet Application instance
     */
    protected $app;

    /**
     * @var string Root storage path
     */
    protected $root;

    /**
     * Initialize filesystem with application configuration
     *
     * @param Rivulet $app Application instance
     * @throws Exception If root directory cannot be created
     */
    public function __construct(Rivulet $app)
    {
        $this->app  = $app;
        $this->root = $app->getConfig('filesystems.disks.local.root');

        if (! is_dir($this->root) && ! mkdir($this->root, 0755, true)) {
            throw new Exception("Failed to create root directory: {$this->root}");
        }
    }

    /**
     * Resolve and validate full filesystem path
     *
     * @param string $path Relative path
     * @return string Absolute path
     * @throws Exception If path is invalid or outside root directory
     */
    protected function resolvePath(string $path): string
    {
        $fullPath = rtrim($this->root, '/') . '/' . ltrim($path, '/');
        $realPath = realpath($fullPath);

        if ($realPath === false || strpos($realPath, realpath($this->root)) !== 0) {
            throw new Exception("Invalid path: {$path}");
        }

        return $realPath;
    }

    public function createFile($path, $content = '')
    {
        $fullPath = $this->root . '/' . ltrim($path, '/');
        if (file_exists($fullPath)) {
            throw new Exception('File already exists');
        }
        $dir = dirname($fullPath);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents($fullPath, $content);
        chmod($fullPath, 0644);
        return $path;
    }
    /**
     * Handle file upload
     *
     * @param array $file Uploaded file data ($_FILES format)
     * @param string $path Destination directory
     * @param string|null $name Custom filename
     * @return string Relative path to uploaded file
     * @throws Exception If upload fails
     */
    public function upload(array $file, string $path = '', ?string $name = null): string
    {
        if (! isset($file['tmp_name']) || ! is_uploaded_file($file['tmp_name'])) {
            throw new Exception('Invalid file upload');
        }

        $destinationDir = rtrim($this->root . '/' . $path, '/');
        if (! is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        $filename    = $name ?? basename($file['name']);
        $destination = $destinationDir . '/' . $filename;

        if (! move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception('File upload failed');
        }

        chmod($destination, 0644);
        return $path . '/' . $filename;
    }

    /**
     * Delete file or directory (recursive)
     *
     * @param string $path Target path
     * @return bool True on success
     */
    public function delete(string $path): bool
    {
        $fullPath = $this->resolvePath($path);

        if (is_file($fullPath)) {
            return unlink($fullPath);
        }

        if (is_dir($fullPath)) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($fullPath, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($iterator as $file) {
                if ($file->isDir()) {
                    rmdir($file->getPathname());
                } else {
                    unlink($file->getPathname());
                }
            }

            return rmdir($fullPath);
        }

        return false;
    }

    /**
     * Copy file or directory (recursive)
     *
     * @param string $source Source path
     * @param string $destination Target path
     * @return bool True on success
     */
    public function copy(string $source, string $destination): bool
    {
        $sourcePath = $this->resolvePath($source);
        $destPath   = $this->root . '/' . ltrim($destination, '/');

        if (is_file($sourcePath)) {
            return copy($sourcePath, $destPath);
        }

        if (is_dir($sourcePath)) {
            if (! is_dir($destPath)) {
                mkdir($destPath, 0755, true);
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($sourcePath, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $item) {
                $subPath = $destPath . '/' . $iterator->getSubPathName();
                if ($item->isDir()) {
                    mkdir($subPath, 0755);
                } else {
                    copy($item, $subPath);
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Move/rename file or directory
     *
     * @param string $source Source path
     * @param string $destination Target path
     * @return bool True on success
     */
    public function move(string $source, string $destination): bool
    {
        $sourcePath = $this->resolvePath($source);
        $destPath   = $this->root . '/' . ltrim($destination, '/');
        return rename($sourcePath, $destPath);
    }

    /**
     * Rename file or directory
     *
     * @param string $path Original path
     * @param string $newName New name
     * @return bool True on success
     */
    public function rename(string $path, string $newName): bool
    {
        $fullPath = $this->resolvePath($path);
        $dir      = dirname($fullPath);
        $newPath  = $dir . '/' . $newName;
        return rename($fullPath, $newPath);
    }

    /**
     * Download file as response
     *
     * @param string $path File path
     * @return Response Download response
     * @throws Exception If file not found
     */
    public function download(string $path): Response
    {
        $fullPath = $this->resolvePath($path);

        if (! is_file($fullPath)) {
            throw new Exception('File not found');
        }

        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
        return new Response(
            file_get_contents($fullPath),
            200,
            [
                'Content-Type'        => $mime,
                'Content-Disposition' => 'attachment; filename="' . basename($fullPath) . '"',
            ]
        );
    }

    /**
     * Create new directory
     *
     * @param string $path Directory path
     * @return bool True if created, false if already exists
     */
    public function createDirectory(string $path): bool
    {
        $fullPath = $this->root . '/' . ltrim($path, '/');
        return ! is_dir($fullPath) && mkdir($fullPath, 0755, true);
    }

    /**
     * Create zip archive of directory
     *
     * @param string $sourceDir Directory to compress
     * @param string|null $zipPath Custom zip file path
     * @return string Name of created zip file
     * @throws Exception If source not found or zip creation fails
     */
    public function zip(string $sourceDir, ?string $zipPath = null): string
    {
        $sourcePath = $this->resolvePath($sourceDir);

        if (! is_dir($sourcePath)) {
            throw new Exception('Source directory not found');
        }

        $zipFile = $zipPath
        ? $this->root . '/' . ltrim($zipPath, '/')
        : $this->root . '/' . basename($sourceDir) . '.zip';

        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new Exception('Cannot create zip file');
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sourcePath)
        );

        foreach ($iterator as $file) {
            if (! $file->isDir()) {
                $relativePath = substr($file->getPathname(), strlen($sourcePath) + 1);
                $zip->addFile($file->getPathname(), $relativePath);
            }
        }

        $zip->close();
        return basename($zipFile);
    }

    /**
     * Extract zip archive
     *
     * @param string $zipPath Zip file path
     * @param string $extractTo Destination directory
     * @return bool True on success
     * @throws Exception If zip file not found or extraction fails
     */
    public function unzip(string $zipPath, string $extractTo = ''): bool
    {
        $zipFullPath = $this->resolvePath($zipPath);

        if (! is_file($zipFullPath)) {
            throw new Exception('Zip file not found');
        }

        $extractDir = $extractTo
        ? $this->root . '/' . ltrim($extractTo, '/')
        : $this->root;

        if (! is_dir($extractDir)) {
            mkdir($extractDir, 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipFullPath) !== true) {
            throw new Exception('Cannot open zip file');
        }

        $zip->extractTo($extractDir);
        $zip->close();
        return true;
    }
}
