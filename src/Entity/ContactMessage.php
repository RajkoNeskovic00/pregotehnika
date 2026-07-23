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
     * @Assert\NotBlank(message="Ime i prezime / Naziv firme je obavezno polje.")
     *
     * @Assert\Length(min=3, minMessage="Ime mora imati najmanje {{ limit }} karaktera.")
     */
    private $full_name;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Email je obavezno polje.")
     *
     * @Assert\Email(message="Unesite validnu email adresu. Email mora da sadrži @ u sebi")
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank(message="Poruka ne sme biti prazna.")
     *
     * @Assert\Length(min=3, minMessage="Poruka mora imati najmanje {{ limit }} karaktera.")
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\NotBlank(message="Broj telefona je obavezno polje.")
     *
     * @Assert\Regex(pattern="/^[0-9\s\-\+\/]+$/", message="Unesite validan broj telefona.")
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
