<?php

namespace App\Entity;

use App\Enum\ContactPreference;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $full_name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $phone_number = null;

    #[ORM\Column(enumType: ContactPreference::class)]
    private ?ContactPreference $contactPreference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $brand_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $instagram_account = null;

    #[ORM\Column]
    private ?\DateTime $created_at = null;

    /**
     * @var Collection<int, Projects>
     */
    #[ORM\OneToMany(targetEntity: Projects::class, mappedBy: 'client')]
    private Collection $client_id;

    public function __construct()
    {
        $this->client_id = new ArrayCollection();
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->full_name;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): static
    {
        $this->full_name = $full_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): static
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getContactPreference(): ?ContactPreference
    {
        return $this->contactPreference;
    }

    public function setContactPreference(ContactPreference $contactPreference): static
    {
        $this->contactPreference = $contactPreference;

        return $this;
    }

    public function getBrandName(): ?string
    {
        return $this->brand_name;
    }

    public function setBrandName(?string $brand_name): static
    {
        $this->brand_name = $brand_name;

        return $this;
    }

    public function getInstagramAccount(): ?string
    {
        return $this->instagram_account;
    }

    public function setInstagramAccount(?string $instagram_account): static
    {
        $this->instagram_account = $instagram_account;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Projects>
     */
    public function getClientId(): Collection
    {
        return $this->client_id;
    }

    public function addClientId(Projects $clientId): static
    {
        if (!$this->client_id->contains($clientId)) {
            $this->client_id->add($clientId);
            $clientId->setClient($this);
        }

        return $this;
    }

    public function removeClientId(Projects $clientId): static
    {
        if ($this->client_id->removeElement($clientId)) {
            // set the owning side to null (unless already changed)
            if ($clientId->getClient() === $this) {
                $clientId->setClient(null);
            }
        }

        return $this;
    }
}
