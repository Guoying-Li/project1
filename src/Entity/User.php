<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Picture::class)]
    private Collection $createdPicture;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Event::class)]
    private Collection $createdEvent;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'sharedTo')]
    private Collection $sharedEvents;

    public function __construct()
    {
        $this->createdPicture = new ArrayCollection();
        $this->createdEvent = new ArrayCollection();
        $this->sharedEvents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getCreatedPicture(): Collection
    {
        return $this->createdPicture;
    }

    public function addCreatedPicture(Picture $createdPicture): static
    {
        if (!$this->createdPicture->contains($createdPicture)) {
            $this->createdPicture->add($createdPicture);
            $createdPicture->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCreatedPicture(Picture $createdPicture): static
    {
        if ($this->createdPicture->removeElement($createdPicture)) {
            // set the owning side to null (unless already changed)
            if ($createdPicture->getCreatedBy() === $this) {
                $createdPicture->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getCreatedEvent(): Collection
    {
        return $this->createdEvent;
    }

    public function addCreatedEvent(Event $createdEvent): static
    {
        if (!$this->createdEvent->contains($createdEvent)) {
            $this->createdEvent->add($createdEvent);
            $createdEvent->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCreatedEvent(Event $createdEvent): static
    {
        if ($this->createdEvent->removeElement($createdEvent)) {
            // set the owning side to null (unless already changed)
            if ($createdEvent->getCreatedBy() === $this) {
                $createdEvent->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getSharedEvents(): Collection
    {
        return $this->sharedEvents;
    }

    public function addSharedEvent(Event $sharedEvent): static
    {
        if (!$this->sharedEvents->contains($sharedEvent)) {
            $this->sharedEvents->add($sharedEvent);
            $sharedEvent->addSharedTo($this);
        }

        return $this;
    }

    public function removeSharedEvent(Event $sharedEvent): static
    {
        if ($this->sharedEvents->removeElement($sharedEvent)) {
            $sharedEvent->removeSharedTo($this);
        }

        return $this;
    }
    public function getFullname(): string
    {
        return  $this->firstname . ' '. $this->lastname . '';
    }
    public function __toString()
    {
        return $this->id;
    }
}
