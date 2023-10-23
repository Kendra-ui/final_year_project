<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Problem::class)]
    private Collection $problems;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductCart::class)]
    private Collection $productCarts;

    #[ORM\Column]
    private ?int $pQuantity = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductProperty::class)]
    private Collection $productProperties;


    public function __construct()
    {
        $this->problems = new ArrayCollection();
        $this->productCarts = new ArrayCollection();
        $this->productProperties = new ArrayCollection();
    }

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

   

    /**
     * @return Collection<int, Problem>
     */
    public function getProblems(): Collection
    {
        return $this->problems;
    }

    public function addProblem(Problem $problem): static
    {
        if (!$this->problems->contains($problem)) {
            $this->problems->add($problem);
            $problem->setProduct($this);
        }

        return $this;
    }

    public function removeProblem(Problem $problem): static
    {
        if ($this->problems->removeElement($problem)) {
            // set the owning side to null (unless already changed)
            if ($problem->getProduct() === $this) {
                $problem->setProduct(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, ProductCart>
     */
    public function getProductCarts(): Collection
    {
        return $this->productCarts;
    }

    public function addProductCart(ProductCart $productCart): static
    {
        if (!$this->productCarts->contains($productCart)) {
            $this->productCarts->add($productCart);
            $productCart->setProduct($this);
        }

        return $this;
    }

    public function removeProductCart(ProductCart $productCart): static
    {
        if ($this->productCarts->removeElement($productCart)) {
            // set the owning side to null (unless already changed)
            if ($productCart->getProduct() === $this) {
                $productCart->setProduct(null);
            }
        }

        return $this;
    }

    public function getPQuantity(): ?int
    {
        return $this->pQuantity;
    }

    public function setPQuantity(int $pQuantity): static
    {
        $this->pQuantity = $pQuantity;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

   
    /**
     * @return Collection<int, ProductProperty>
     */
    public function getProductProperties(): Collection
    {
        return $this->productProperties;
    }

    public function addProductProperties(ProductProperty $productProperties): static
    {
        if (!$this->productProperties->contains($productProperties)) {
            $this->productProperties->add($productProperties);
            $productProperties->setProduct($this);
        }

        return $this;
    }

    public function removeProductProperties(ProductProperty $productProperties): static
    {
        if ($this->productProperties->removeElement($productProperties)) {
            // set the owning side to null (unless already changed)
            if ($productProperties->getProduct() === $this) {
                $productProperties->setProduct(null);
            }
        }

        return $this;
    }

 
}
