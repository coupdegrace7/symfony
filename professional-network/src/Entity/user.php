<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: "App\Repository\UserRepository")]
#[ORM\Table(name: "users")]
class User
{
    #[ORM\Id, ORM\Column(type: "string", length: 36)]
    private string $id;

    #[ORM\Column(type: "string", length: 100)]
    private string $firstName;

    #[ORM\Column(type: "string", length: 100)]
    private string $lastName;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $bio = null;

    #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: "users", cascade: ["persist"])]
    #[ORM\JoinTable(name: "user_skills")]
    private Collection $skills;

    #[ORM\Column(type: "json")]
    private array $roles = [];

    #[ORM\Column(type: "string", length: 100, unique: true)]
    private string $email;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $email, string $firstName, string $lastName)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->skills = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->roles = ['ROLE_USER'];
    }

    // --- getters/setters ---
    public function getId(): string { return $this->id; }
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function getFirstName(): string { return $this->firstName; }
    public function setFirstName(string $v): self { $this->firstName = $v; return $this; }
    public function getLastName(): string { return $this->lastName; }
    public function setLastName(string $v): self { $this->lastName = $v; return $this; }
    public function getFullName(): string { return trim($this->firstName . ' ' . $this->lastName); }
    public function getBio(): ?string { return $this->bio; }
    public function setBio(?string $b): self { $this->bio = $b; return $this; }

    public function getSkills(): Collection { return $this->skills; }
    public function addSkill(Skill $s): self { if (!$this->skills->contains($s)) $this->skills->add($s); return $this; }
    public function removeSkill(Skill $s): self { $this->skills->removeElement($s); return $this; }

    public function getRoles(): array { return $this->roles; }
    public function setRoles(array $roles): self { $this->roles = $roles; return $this; }
}
