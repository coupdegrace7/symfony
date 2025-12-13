<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\ModerationLogRepository")]
#[ORM\Table(name:"moderation_logs")]
class ModerationLog {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:50)]
    private string $entityType;

    #[ORM\Column(type:"integer")]
    private int $entityId;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $moderator;

    #[ORM\Column(type:"string", length:50)]
    private string $action; // edit|delete|flag

    #[ORM\Column(type:"json", nullable:true)]
    private ?array $beforeState = null;

    #[ORM\Column(type:"json", nullable:true)]
    private ?array $afterState = null;

    #[ORM\Column(type:"datetime_immutable")]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $entityType, int $entityId, ?User $moderator, string $action) {
        $this->entityType = $entityType; $this->entityId = $entityId; $this->moderator = $moderator; $this->action = $action;
        $this->createdAt = new \DateTimeImmutable();
    }
}
