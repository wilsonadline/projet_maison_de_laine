<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    
    /**
     * @ORM\OneToMany(targetEntity=OrderLine::class, mappedBy="orders")
     */
    private $orderLines;

    /**
     * @ORM\ManyToOne(targetEntity=OrderStatus::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderStatus;

    /**
     * @ORM\ManyToOne(targetEntity=Adresses::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $adresse;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    // /**
    //  * @ORM\OneToOne(targetEntity=Delivry::class, cascade={"persist", "remove"})
    //  */
    // private $delivry;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity=Delivry::class, inversedBy="orders")
     */
    private $delivery;

    // /**
    //  * @ORM\ManyToOne(targetEntity=Delivry::class, inversedBy="orders")
    //  * @ORM\JoinColumn(nullable=true)
    //  */
    // private $Delivery;



    public function __construct()
    {
        $this->orderLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    
    /**
     * @return Collection|OrderLine[]
     */
    public function getOrderLines(): Collection
    {
        return $this->orderLines;
    }

    public function addOrderLine(OrderLine $orderLine): self
    {
        if (!$this->orderLines->contains($orderLine)) {
            $this->orderLines[] = $orderLine;
            $orderLine->setOrders($this);
        }

        return $this;
    }

    public function removeOrderLine(OrderLine $orderLine): self
    {
        if ($this->orderLines->removeElement($orderLine)) {
            // set the owning side to null (unless already changed)
            if ($orderLine->getOrders() === $this) {
                $orderLine->setOrders(null);
            }
        }

        return $this;
    }

    public function getOrderStatus(): ?OrderStatus
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(?OrderStatus $orderStatus): self
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    public function getAdresse(): ?Adresses
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresses $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    } 

    public function computeTotal()
    {
        $total = 0 ;
        foreach ( $this->orderLines as $line)
        {
            $total += $line->getQuantite() * $line->getPrix();
        }
        return $total;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    // public function getDelivry(): ?Delivry
    // {
    //     return $this->delivry;
    // }

    // public function setDelivry(?Delivry $delivry): self
    // {
    //     $this->delivry = $delivry;

    //     return $this;
    // }

    public function setTotal(?float $total): self
    {
        $this->total = $total;

        return $this;
    }

    // public function getDelivery(): ?Delivry
    // {
    //     return $this->Delivery;
    // }

    // public function setDelivery(?Delivry $Delivery): self
    // {
    //     $this->Delivery = $Delivery;

    //     return $this;
    // }

    public function getDelivery(): ?Delivry
    {
        return $this->delivery;
    }

    public function setDelivery(?Delivry $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }
}
