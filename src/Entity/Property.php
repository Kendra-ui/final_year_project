<?php

namespace App\Entity;

use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'property', targetEntity: ProductProperty::class)]
    private Collection $productProperties;

    #[ORM\Column(length: 255)]
    private ?string $benefits = null;

    public function __construct()
    {
        $this->productProperties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return Collection<int, productProperties>
     */
    public function getproductProperties(): Collection
    {
        return $this->productProperties;
    }

    public function addproductProperties(productProperties $productProperties): static
    {
        if (!$this->productProperties->contains($productProperties)) {
            $this->productProperties->add($productProperties);
            $productProperties->setProperty($this);
        }

        return $this;
    }

    public function removeproductProperties(productProperties $productProperties): static
    {
        if ($this->productProperties->removeElement($productProperties)) {
            // set the owning side to null (unless already changed)
            if ($productProperties->getProperty() === $this) {
                $productProperties->setProperty(null);
            }
        }

        return $this;
    }

    public function getBenefits(): ?string
    {
        return $this->benefits;
    }

    public function setBenefits(string $benefits): static
    {
        $this->benefits = $benefits;

        return $this;
    }
}
