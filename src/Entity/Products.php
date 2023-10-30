<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'product')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categories $categories = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Suppliers $supp_id = null;

    #[ORM\OneToMany(mappedBy: 'products', targetEntity: ProductsOrders::class)]
    private Collection $productsOrders;

    public function __construct()
    {
        $this->productsOrders = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

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

    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): static
    {
        $this->categories = $categories;

        return $this;
    }

    public function getSuppId(): ?Suppliers
    {
        return $this->supp_id;
    }

    public function setSuppId(?Suppliers $supp_id): static
    {
        $this->supp_id = $supp_id;

        return $this;
    }

    /**
     * @return Collection<int, ProductsOrders>
     */
    public function getProductsOrders(): Collection
    {
        return $this->productsOrders;
    }

    public function addProductsOrder(ProductsOrders $productsOrder): static
    {
        if (!$this->productsOrders->contains($productsOrder)) {
            $this->productsOrders->add($productsOrder);
            $productsOrder->setProducts($this);
        }

        return $this;
    }

    public function removeProductsOrder(ProductsOrders $productsOrder): static
    {
        if ($this->productsOrders->removeElement($productsOrder)) {
            // set the owning side to null (unless already changed)
            if ($productsOrder->getProducts() === $this) {
                $productsOrder->setProducts(null);
            }
        }

        return $this;
    }
}
