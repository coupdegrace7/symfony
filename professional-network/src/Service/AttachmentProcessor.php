<?php
namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class AttachmentProcessor
{
    private Filesystem $fs;
    public function __construct() { $this->fs = new Filesystem(); }

    public function process(string $path): array
    {
        // примеры: создание превью, проверка типа, перемещение в хранилище (S3)
        // В demo: просто проверим наличие и вернём мета
        if (!$this->fs->exists($path)) {
            throw new \RuntimeException("File not found: $path");
        }
        $info = pathinfo($path);
        return [
            'path' => $path,
            'basename' => $info['basename'] ?? '',
            'size' => filesize($path),
        ];
    }
}
