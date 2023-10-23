<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $sessionId = null;


    #[ORM\OneToOne(inversedBy: 'cart', cascade: ['persist', 'remove'])]
    private ?Payment $payment;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: ProductCart::class)]
    private Collection $ProductCarts;



    public function __construct()
    {
        $this->ProductCarts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(string $sessionId): self
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(Payment $payment): static
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return Collection<int, ProductCart>
     */
    public function getProductCarts(): Collection
    {
        return $this->ProductCarts;
    }

    public function addProductCart(ProductCart $productCart): static
    {
        if (!$this->ProductCarts->contains($productCart)) {
            $this->ProductCarts->add($productCart);
            $productCart->setCart($this);
        }

        return $this;
    }

    public function removeProductCart(ProductCart $productCart): static
    {
        if ($this->ProductCarts->removeElement($productCart)) {
            // set the owning side to null (unless already changed)
            if ($productCart->getCart() === $this) {
                $productCart->setCart(null);
            }
        }

        return $this;
    }

    //calculate the total of the cart
    public function totalPrice()
    {
        $total = 0;
       foreach($this->getproductCarts() as $k => $productCart){
          $total += $productCart->getquantity() * $productCart->getProduct()->getPrice();
       }
         return $total;
    }

    
    //calculate the products of the cart
    public function totalProducts()
    {
        $total = 0;
       foreach($this->getproductCarts() as $k => $productCart){
          $total += $productCart->getquantity();
       }
         return $total;
    }

  

    
}
