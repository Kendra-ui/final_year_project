<?php

namespace App\Entity;

use App\Repository\SubscriberRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubscriberRepository::class)]
class Subscriber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $skintype = null;

    #[ORM\Column(length: 255)]
    private ?string $skinproblem = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSkintype(): ?string
    {
        return $this->skintype;
    }

    public function setSkintype(string $skintype): static
    {
        $this->skintype = $skintype;

        return $this;
    }

    public function getSkinproblem(): ?string
    {
        return $this->skinproblem;
    }

    public function setSkinproblem(string $skinproblem): static
    {
        $this->skinproblem = $skinproblem;

        return $this;
    }
}
