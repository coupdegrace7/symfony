<?php
namespace App\Message;

class ProcessAttachmentMessage
{
    public function __construct(
        public string $filePath,
        public int $postId
    ) {}
}
