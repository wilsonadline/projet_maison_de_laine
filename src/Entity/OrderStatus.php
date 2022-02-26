<?php

namespace App\Entity;

use App\Repository\OrderStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity(repositoryClass=OrderStatusRepository::class)
*/
class OrderStatus
{
    /**
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(type="integer")
    */
    private $id;

    /**
    * @ORM\Column(type="string", length=255)
    */
    private $status;

    /**
    * @ORM\OneToMany(targetEntity=Order::class, mappedBy="orderStatus")
    */
    private $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
    * @return Collection|Order[]
    */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if(!$this->orders->contains($order)){
            $this->orders[] = $order;
            $order->setOrderStatus($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if($this->orders->removeElement($order)){
            // set the owning side to null (unless already changed)
            if($order->getOrderStatus() === $this){
                $order->setOrderStatus(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }
}