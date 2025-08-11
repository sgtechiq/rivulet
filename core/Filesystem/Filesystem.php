<?php

namespace Rivulet\Filesystem;

use Rivulet\Rivulet;
use Rivulet\Http\Response;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Exception;

class Filesystem {
    protected $app;
    protected $root;

    public function __construct(Rivulet $app) {
        $this->app = $app;
        $this->root = $app->getConfig('filesystems.disks.local.root');
        if (!is_dir($this->root)) {
            mkdir($this->root, 0755, true);
        }
    }

    protected function resolvePath($path) {
        $fullPath = rtrim($this->root, '/') . '/' . ltrim($path, '/');
        $realPath = realpath($fullPath);
        if ($realPath === false || strpos($realPath, realpath($this->root)) !== 0) {
            throw new Exception("Invalid path: {$path}");
        }
        return $realPath;
    }

    public function upload($file, $path = '', $name = null) {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new Exception('Invalid file upload');
        }
        $destinationDir = rtrim($this->root . '/' . $path, '/');
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }
        $filename = $name ?? basename($file['name']);
        $destination = $destinationDir . '/' . $filename;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            chmod($destination, 0644);
            return $path . '/' . $filename;
        }
        throw new Exception('File upload failed');
    }

    public function delete($path) {
        $fullPath = $this->resolvePath($path);
        if (is_file($fullPath)) {
            return unlink($fullPath);
        } elseif (is_dir($fullPath)) {
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

    public function copy($source, $destination) {
        $sourcePath = $this->resolvePath($source);
        $destPath = $this->root . '/' . ltrim($destination, '/');
        if (is_file($sourcePath)) {
            return copy($sourcePath, $destPath);
        } elseif (is_dir($sourcePath)) {
            if (!is_dir($destPath)) {
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

    public function move($source, $destination) {
        $sourcePath = $this->resolvePath($source);
        $destPath = $this->root . '/' . ltrim($destination, '/');
        return rename($sourcePath, $destPath);
    }

    public function rename($path, $newName) {
        $fullPath = $this->resolvePath($path);
        $dir = dirname($fullPath);
        $newPath = $dir . '/' . $newName;
        return rename($fullPath, $newPath);
    }

    public function download($path) {
        $fullPath = $this->resolvePath($path);
        if (!is_file($fullPath)) {
            throw new Exception('File not found');
        }
        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
        $response = new Response(file_get_contents($fullPath), 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'attachment; filename="' . basename($fullPath) . '"',
        ]);
        return $response;
    }

    public function createDirectory($path) {
        $fullPath = $this->root . '/' . ltrim($path, '/');
        if (!is_dir($fullPath)) {
            return mkdir($fullPath, 0755, true);
        }
        return false;
    }

    public function zip($sourceDir, $zipPath = null) {
        $sourcePath = $this->resolvePath($sourceDir);
        if (!is_dir($sourcePath)) {
            throw new Exception('Source directory not found');
        }
        $zipFile = $zipPath ? $this->root . '/' . ltrim($zipPath, '/') : $this->root . '/' . basename($sourceDir) . '.zip';
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new Exception('Cannot create zip file');
        }
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourcePath));
        foreach ($iterator as $file) {
            if (!$file->isDir()) {
                $zip->addFile($file->getPathname(), substr($file->getPathname(), strlen($sourcePath) + 1));
            }
        }
        $zip->close();
        return basename($zipFile);
    }

    public function unzip($zipPath, $extractTo = '') {
        $zipFullPath = $this->resolvePath($zipPath);
        if (!is_file($zipFullPath)) {
            throw new Exception('Zip file not found');
        }
        $extractDir = $extractTo ? $this->root . '/' . ltrim($extractTo, '/') : $this->root;
        if (!is_dir($extractDir)) {
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