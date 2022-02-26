<?php

namespace App\Entity;

use App\Repository\TypeCategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity(repositoryClass=TypeCategoriesRepository::class)
*/
class TypeCategories
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
    private $nom;

    /**
    * @ORM\Column(type="datetime")
    */
    private $createdAt;

    /**
    * @ORM\Column(type="datetime", nullable=true)
    */
    private $updatedAt;

    /**
    * @ORM\OneToMany(targetEntity=Categories::class, mappedBy="typeCategories")
    */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAT = $updatedAt;

        return $this;
    }

    /**
    * @return Collection|Categories[]
    */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): self
    {
        if(!$this->categories->contains($category)){
            $this->categories[] = $category;
            $category->setTypeCategories($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        if($this->categories->removeElement($category)){
            // set the owning side to null (unless already changed)
            if($category->getTypeCategories() === $this){
                $category->setTypeCategories(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nom;
        return $this->categories;
    }
    
}