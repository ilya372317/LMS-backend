<?php

namespace App\Entity;

use App\Enum\User\UserRole;
use App\Repository\UserRepository;
use App\Validator\UniqueEntityValue;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 250,
        minMessage: "Username should have at least 3 symbols",
        maxMessage: "Username should have less then 250 symbols"
    )]
    #[UniqueEntityValue(self::class, 'username')]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    #[Assert\Type(Types::STRING)]
    private ?string $password = null;

    #[ORM\Column(length: 500, unique: true, nullable: true)]
    #[Assert\Email]
    #[UniqueEntityValue(self::class, 'email')]
    private string $email;

    #[ORM\Column(type: Types::BOOLEAN)]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CourseAccess::class, orphanRemoval: true)]
    private Collection $courseAccesses;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->courseAccesses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->username;
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

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(UserRole $role)
    {
        $this->roles[] = $role->value;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function __toString(): string
    {
        return $this->username . "\n"
            . $this->email . "\n"
            . $this->id;

    }

    /**
     * @return Collection<int, CourseAccess>
     */
    public function getCourseAccesses(): Collection
    {
        return $this->courseAccesses;
    }

    public function addCourseAccess(CourseAccess $courseAccess): self
    {
        if (!$this->courseAccesses->contains($courseAccess)) {
            $this->courseAccesses->add($courseAccess);
            $courseAccess->setUser($this);
        }

        return $this;
    }

    public function removeCourseAccess(CourseAccess $courseAccess): self
    {
        if ($this->courseAccesses->removeElement($courseAccess)) {
            // set the owning side to null (unless already changed)
            if ($courseAccess->getUser() === $this) {
                $courseAccess->setUser(null);
            }
        }

        return $this;
    }

    #[ORM\PreUpdate]
    public function onUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function onCreate(): void
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }
}
