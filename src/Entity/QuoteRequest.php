<?php

namespace App\Entity;

use App\Enum\ContactPreference;
use App\Enum\MediaType;
use App\Enum\QuoteRequestStatus;
use App\Enum\ShootType;
use App\Repository\QuoteRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: QuoteRequestRepository::class)]
class QuoteRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[ORM\Column(length: 50)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(enumType: ShootType::class)]
    private ?ShootType $shootType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $brandName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $instagramAccount = null;

    #[ORM\Column(enumType: ContactPreference::class)]
    private ?ContactPreference $contactPreference = null;

    #[ORM\Column(enumType: MediaType::class)]
    #[Assert\Choice(choices: [MediaType::PHOTOS, MediaType::VIDEOS, MediaType::BOTH])]
    private ?MediaType $mediaType = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contentQuantity = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $numberOfItems = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $productType = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $creativeDescription = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    #[Assert\Callback]
    public function validateStartDate(ExecutionContextInterface $context): void
    {
        if ($this->startDate === null) {
            return;
        }

        $tomorrow = new \DateTimeImmutable('tomorrow');

        if ($this->startDate < $tomorrow) {
            $context
                ->buildViolation('quote_request.errors.start_date_invalid')
                ->atPath('startDate')
                ->addViolation();
        }
    }

    #[ORM\Column(enumType: QuoteRequestStatus::class)]
    private QuoteRequestStatus $status = QuoteRequestStatus::PENDING;

    #[ORM\Column]
    private ?\DateTime $created_at = null;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

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

    public function getShootType(): ?ShootType
    {
        return $this->shootType;
    }

    public function setShootType(ShootType $shootType): static
    {
        $this->shootType = $shootType;

        return $this;
    }

    public function getBrandName(): ?string
    {
        return $this->brandName;
    }

    public function setBrandName(?string $brandName): static
    {
        $this->brandName = $brandName;

        return $this;
    }

    public function getInstagramAccount(): ?string
    {
        return $this->instagramAccount;
    }

    public function setInstagramAccount(?string $instagramAccount): static
    {
        $this->instagramAccount = $instagramAccount;

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

    public function getMediaType(): ?MediaType
    {
        return $this->mediaType;
    }

    public function setMediaType(MediaType $mediaType): static
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    public function getContentQuantity(): ?string
    {
        return $this->contentQuantity;
    }

    public function setContentQuantity(?string $contentQuantity): static
    {
        $this->contentQuantity = $contentQuantity;

        return $this;
    }

    public function getNumberOfItems(): ?string
    {
        return $this->numberOfItems;
    }

    public function setNumberOfItems(?string $numberOfItems): static
    {
        $this->numberOfItems = $numberOfItems;

        return $this;
    }

    public function getProductType(): ?string
    {
        return $this->productType;
    }

    public function setProductType(?string $productType): static
    {
        $this->productType = $productType;

        return $this;
    }

    public function getCreativeDescription(): ?string
    {
        return $this->creativeDescription;
    }

    public function setCreativeDescription(?string $creativeDescription): static
    {
        $this->creativeDescription = $creativeDescription;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getStatus(): QuoteRequestStatus
    {
        return $this->status;
    }

    public function setStatus(QuoteRequestStatus $status): static
    {
        $this->status = $status;

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
}
