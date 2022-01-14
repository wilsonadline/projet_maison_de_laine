<?php

namespace App\Entity;

use App\Entity\Categories;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
* @ORM\Entity(repositoryClass="App\Repository\ArticlesRepository")
* @Vich\Uploadable
*/
class Articles
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
    private $article;

    /**
    * @ORM\Column(type="string", length=255, nullable=true)
    */
    private $couleur;

    /**
    * @ORM\Column(type="text", nullable=true)
    */
    private $description;

    /**
    * @ORM\Column(type="float")
    */
    private $prix;

    /**
    * @ORM\Column(type="datetime", nullable=true)
    */
    private $createdAt;

    /**
    * @ORM\Column(type="datetime", nullable=true)
    */
    private $updatedAt;


    /**
    * @Vich\UploadableField(mapping="article_img", fileNameProperty="imageName")
    * @var File|null
    */
    private $imageFile;

    /**
    * @ORM\Column(type="string", nullable=true)
    * @var string|null
    */
    private $imageName;

    /**
    * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="articles")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $categories;

    /**
    * @ORM\Column(type="integer", nullable=true)
    */
    private $stock;

    /**
    * @ORM\OneToMany(targetEntity=OrderLine::class, mappedBy="articles")
    */
    private $orderLines;

    // /**
    //  * @ORM\Column(type="boolean")
    //  */
    // private $active;
   
    public function __construct()
    {
        $this->orderLines = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function setArticle(string $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

        public function setImageName(?string $imageName): void
        {
            $this->imageName = $imageName;
        }

    public function getImageName(): ?string
    {
        return $this->imageName;
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
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): self
    {
        $this->categories = $categories;

        return $this;
    }
    public function __toString() {
        return $this->article;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

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
            $orderLine->setArticle($this);
        }

        return $this;
    }

    public function removeOrderLine(OrderLine $orderLine): self
    {
        if ($this->orderLines->removeElement($orderLine)) {
            // set the owning side to null (unless already changed)
            if ($orderLine->getArticle() === $this) {
                $orderLine->setArticle(null);
            }
        }

        return $this;
    }

    // public function getActive(): ?bool
    // {
    //     return $this->active;
    // }

    // public function setActive(bool $active): self
    // {
    //     $this->active = $active;

    //     return $this;
    // }
}
