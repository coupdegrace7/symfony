<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\NotificationRepository")]
#[ORM\Table(name:"notifications")]
class Notification
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable:false)]
    private User $user;

    #[ORM\Column(type:"string", length:100)]
    private string $eventType;

    #[ORM\Column(type:"json")]
    private array $payload;

    #[ORM\Column(type:"datetime_immutable")]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type:"datetime_immutable", nullable:true)]
    private ?\DateTimeImmutable $readAt = null;

    public function __construct(User $user, string $eventType, array $payload) {
        $this->user = $user;
        $this->eventType = $eventType;
        $this->payload = $payload;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function markRead(): self { $this->readAt = new \DateTimeImmutable(); return $this; }
}
