<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContactMessageRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ContactMessageRepository::class)
 *
 * @ORM\Table(name="contact_messages")
 */
class ContactMessage
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
     *
     * @Assert\NotBlank(message="contact.validation.full_name_required")
     *
     * @Assert\Length(min=3, minMessage="contact.validation.full_name_min")
     */
    private $full_name;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="contact.validation.email_required")
     *
     * @Assert\Email(message="contact.validation.email_invalid")
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank(message="contact.validation.message_required")
     *
     * @Assert\Length(min=3, minMessage="contact.validation.message_min")
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\NotBlank(message="contact.validation.phone_required")
     *
     * @Assert\Regex(pattern="/^[0-9\s\-\+\/]+$/", message="contact.validation.phone_invalid")
     */
    private $phone_number;

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

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): self
    {
        $this->full_name = $full_name;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

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
     * Get the value of email.
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email.
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
