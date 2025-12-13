<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: "App\Repository\PostRepository")]
#[ORM\Table(name: "posts")]
class Post
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable:false)]
    private User $author;

    #[ORM\Column(type:"text")]
    private string $text;

    #[ORM\Column(type:"string", length:20)]
    private string $visibility = 'public'; // public|connections|private|company

    #[ORM\Column(type:"json", nullable:true)]
    private ?array $attachments = null; // metadata

    #[ORM\Column(type:"integer")]
    private int $likesCount = 0;

    #[ORM\Column(type:"integer")]
    private int $commentsCount = 0;

    #[ORM\Column(type:"datetime_immutable")]
    private \DateTimeImmutable $createdAt;

    public function __construct(User $author, string $text)
    {
        $this->author = $author;
        $this->text = $text;
        $this->createdAt = new \DateTimeImmutable();
    }

    // getters/setters...
}
