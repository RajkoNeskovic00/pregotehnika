<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductImageRepository;

/**
 * @ORM\Entity(repositoryClass=ProductImageRepository::class)
 *
 * @ORM\Table(name="product_images")
 *
 * @ORM\HasLifecycleCallbacks
 */
class ProductImage
{
    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="images")
     *
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imagePath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $altText;

    /**
     * @ORM\Column(type="integer")
     */
    private $position = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isMain = false;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * Get the value of id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id.
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of product.
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * Set the value of product.
     */
    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get the value of imagePath.
     */
    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    /**
     * Set the value of imagePath.
     */
    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * Get the value of altText.
     */
    public function getAltText(): ?string
    {
        return $this->altText;
    }

    /**
     * Set the value of altText.
     */
    public function setAltText(?string $altText): self
    {
        $this->altText = $altText;

        return $this;
    }

    /**
     * Get the value of position.
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * Set the value of position.
     */
    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get the value of isMain.
     */
    public function getIsMain(): bool
    {
        return $this->isMain;
    }

    /**
     * Set the value of isMain.
     */
    public function setIsMain(bool $isMain): self
    {
        $this->isMain = $isMain;

        return $this;
    }

    #[ORM\PrePersist]
    public function onCreate(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * Get the value of createdAt.
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt.
     */
    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt.
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt.
     */
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PreRemove
     */
    public function deleteFile(): void
    {
        if (!$this->imagePath) {
            return;
        }

        $filePath = __DIR__.'/../../public/uploads/products/'.$this->imagePath;

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }
}
