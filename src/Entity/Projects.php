<?php

namespace App\Entity;

use App\Enum\MediaType;
use App\Enum\ShootType;
use App\Repository\ProjectsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ProjectsRepository::class)]
class Projects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'client_id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\Column(length: 255, enumType: ShootType::class)]
    private ?ShootType $shootType = null;

    #[ORM\Column(length: 255, enumType: MediaType::class)]
    private ?MediaType $mediaType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $content_quantity = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $number_of_items = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $product_type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $creativeDescription = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $raw_folder_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $edited_folder_id = null;

    #[ORM\Column]
    private ?\DateTime $created_at = null;

    /**
     * @var Collection<int, Models>
     */
    #[ORM\ManyToMany(targetEntity: Models::class, inversedBy: 'projects')]
    private Collection $models;

    /**
     * @var Collection<int, TeamMembers>
     */
    #[ORM\ManyToMany(targetEntity: TeamMembers::class, inversedBy: 'projects')]
    private Collection $teamMembers;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->models = new ArrayCollection();
        $this->teamMembers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

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
        return $this->content_quantity;
    }

    public function setContentQuantity(?string $content_quantity): static
    {
        $this->content_quantity = $content_quantity;

        return $this;
    }

    public function getNumberOfItems(): ?string
    {
        return $this->number_of_items;
    }

    public function setNumberOfItems(?string $number_of_items): static
    {
        $this->number_of_items = $number_of_items;

        return $this;
    }

    public function getProductType(): ?string
    {
        return $this->product_type;
    }

    public function setProductType(?string $product_type): static
    {
        $this->product_type = $product_type;

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
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getRawFolderId(): ?string
    {
        return $this->raw_folder_id;
    }

    public function setRawFolderId(?string $raw_folder_id): static
    {
        $this->raw_folder_id = $raw_folder_id;

        return $this;
    }

    public function getEditedFolderId(): ?string
    {
        return $this->edited_folder_id;
    }

    public function setEditedFolderId(?string $edited_folder_id): static
    {
        $this->edited_folder_id = $edited_folder_id;

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
     * @return Collection<int, Models>
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    public function addModel(Models $model): static
    {
        if (!$this->models->contains($model)) {
            $this->models->add($model);
        }

        return $this;
    }

    public function removeModel(Models $model): static
    {
        $this->models->removeElement($model);

        return $this;
    }

    /**
     * @return Collection<int, TeamMembers>
     */
    public function getTeamMembers(): Collection
    {
        return $this->teamMembers;
    }

    public function addTeamMember(TeamMembers $teamMember): static
    {
        if (!$this->teamMembers->contains($teamMember)) {
            $this->teamMembers->add($teamMember);
        }

        return $this;
    }

    public function removeTeamMember(TeamMembers $teamMember): static
    {
        $this->teamMembers->removeElement($teamMember);

        return $this;
    }
}
