<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\CommentRepository")]
#[ORM\Table(name:"comments")]
class Comment
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Post::class)]
    #[ORM\JoinColumn(nullable:false)]
    private Post $post;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable:false)]
    private User $author;

    #[ORM\Column(type:"text")]
    private string $text;

    #[ORM\Column(type:"datetime_immutable")]
    private \DateTimeImmutable $createdAt;

    public function __construct(Post $post, User $author, string $text) {
        $this->post = $post;
        $this->author = $author;
        $this->text = $text;
        $this->createdAt = new \DateTimeImmutable();
    }
}
