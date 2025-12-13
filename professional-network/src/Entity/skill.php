<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: "App\Repository\SkillRepository")]
#[ORM\Table(name:"skills")]
class Skill
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:100, unique:true)]
    private string $name;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: "skills")]
    private Collection $users;

    public function __construct(string $name) {
        $this->name = $name;
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $n): self { $this->name = $n; return $this; }
}
