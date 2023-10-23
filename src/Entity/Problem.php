<?php

namespace App\Entity;

use App\Repository\ProblemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProblemRepository::class)]
class Problem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $skintype = null;

    #[ORM\Column(length: 255)]
    private ?string $skinproblem = null;

    #[ORM\ManyToOne(inversedBy: 'problems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column]
    private ?int $tag = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getTag(): ?int
    {
        return $this->tag;
    }

    public function setTag(int $tag): static
    {
        $this->tag = $tag;

        return $this;
    }
}
