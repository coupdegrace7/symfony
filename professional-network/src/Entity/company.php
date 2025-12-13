<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\CompanyRepository")]
#[ORM\Table(name:"companies")]
class Company {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:150)]
    private string $name;

    #[ORM\Column(type:"text", nullable:true)]
    private ?string $description = null;

    // getters/setters...
}
