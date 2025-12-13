<?php
namespace App\MessageHandler;

use App\Message\ProcessAttachmentMessage;
use App\Service\AttachmentProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Psr\Log\LoggerInterface;

class ProcessAttachmentHandler implements MessageHandlerInterface
{
    public function __construct(private AttachmentProcessor $processor, private EntityManagerInterface $em, private LoggerInterface $logger) {}

    public function __invoke(ProcessAttachmentMessage $msg)
    {
        try {
            $meta = $this->processor->process($msg->filePath);
            // сохранить мета в Post.attachments (в продакшене — связанная сущность или S3 URL)
            $post = $this->em->getRepository(\App\Entity\Post::class)->find($msg->postId);
            if (!$post) {
                $this->logger->warning("Post {$msg->postId} not found for attachment processing");
                return;
            }
            $attachments = $post->getAttachments() ?? [];
            $attachments[] = $meta;
            $post->setAttachments($attachments);
            $this->em->persist($post);
            $this->em->flush();
        } catch (\Throwable $e) {
            $this->logger->error('Attachment processing failed: '.$e->getMessage());
        }
    }
}
