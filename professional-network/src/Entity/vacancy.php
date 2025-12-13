<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\VacancyRepository")]
#[ORM\Table(name:"vacancies")]
class Vacancy {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    private Company $company;

    #[ORM\Column(type:"string", length:200)]
    private string $title;

    #[ORM\Column(type:"text")]
    private string $description;

    // ...
}
