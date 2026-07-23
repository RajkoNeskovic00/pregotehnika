<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="product_documents")
 *
 * @ORM\HasLifecycleCallbacks
 */
class ProductDocument
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
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="documents")
     *
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filePath;

    private ?File $fileUpload = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $position = 0;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /* =========================
     * GETTERS & SETTERS
     * ========================= */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onCreate(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function setFileUpload(?File $fileUpload): self
    {
        $this->fileUpload = $fileUpload;

        if ($fileUpload) {
            if ($this->filePath) {
                $oldFile = __DIR__.'/../../public/uploads/documents/'.$this->filePath;

                if (is_file($oldFile)) {
                    unlink($oldFile);
                }
            }

            $newName = uniqid().'.'.$fileUpload->guessExtension();

            $fileUpload->move(
                __DIR__.'/../../public/uploads/documents',
                $newName,
            );

            $this->filePath = $newName;
        }

        return $this;
    }

    public function getFileUpload(): ?File
    {
        return $this->fileUpload;
    }

    /**
     * @ORM\PreRemove
     */
    public function deleteFile(): void
    {
        if (!$this->filePath) {
            return;
        }

        $path = __DIR__.'/../../public/uploads/documents/'.$this->filePath;

        if (is_file($path)) {
            unlink($path);
        }
    }
}
