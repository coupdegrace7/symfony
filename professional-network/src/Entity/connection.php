<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\ConnectionRepository")]
#[ORM\Table(name: "connections")]
class Connection
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable:false)]
    private User $requester;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable:false)]
    private User $target;

    #[ORM\Column(type:"string", length:20)]
    private string $type = 'friend'; // friend|colleague|follower

    #[ORM\Column(type:"string", length:20)]
    private string $status = 'pending'; // pending|accepted|blocked

    #[ORM\Column(type:"datetime_immutable")]
    private \DateTimeImmutable $createdAt;

    public function __construct(User $requester, User $target, string $type = 'friend')
    {
        $this->requester = $requester;
        $this->target = $target;
        $this->type = $type;
        $this->createdAt = new \DateTimeImmutable();
    }

    // getters / setters ...
}
