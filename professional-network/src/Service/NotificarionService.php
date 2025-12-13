<?php
namespace App\Service;

use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Notification;

class NotificationService
{
    public function __construct(
        private PublisherInterface $publisher,
        private EntityManagerInterface $em
    ) {}

    public function notify(User $to, string $eventType, array $payload): Notification
    {
        $notif = new Notification($to, $eventType, $payload);
        $this->em->persist($notif);
        $this->em->flush();

        $topic = sprintf('/users/%s/notifications', $to->getId());
        $update = new Update($topic, json_encode([
            'id' => $notif->getId(),
            'type' => $eventType,
            'payload' => $payload
        ]));
        ($this->publisher)($update);

        return $notif;
    }
}
