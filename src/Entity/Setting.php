<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SettingRepository;

/**
 * @ORM\Entity(repositoryClass=SettingRepository::class)
 *
 * @ORM\HasLifecycleCallbacks
 *
 * @ORM\Table(name="settings")
 */
class Setting
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
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="text")
     */
    private $value;

    /**
     * @ORM\Column(type="text")
     */
    private $iTag;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PreUpdate
     */
    public function onUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * Get the value of iTag.
     */
    public function getITag()
    {
        return $this->iTag;
    }

    /**
     * Set the value of iTag.
     */
    public function setITag($iTag): self
    {
        $this->iTag = $iTag;

        return $this;
    }
}
