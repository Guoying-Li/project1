<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Picture::class)]
    private Collection $pictures;

    #[ORM\ManyToOne(inversedBy: 'createdEvent')]
    private ?User $createdBy = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPublic = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'sharedEvents')]
    private Collection $sharedTo;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->sharedTo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): static
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setEvent($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): static
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getEvent() === $this) {
                $picture->setEvent(null);
            }
        }

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function isIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(?bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getSharedTo(): Collection
    {
        return $this->sharedTo;
    }

    public function addSharedTo(User $sharedTo): static
    {
        if (!$this->sharedTo->contains($sharedTo)) {
            $this->sharedTo->add($sharedTo);
        }

        return $this;
    }

    public function removeSharedTo(User $sharedTo): static
    {
        $this->sharedTo->removeElement($sharedTo);

        return $this;
    }
}
