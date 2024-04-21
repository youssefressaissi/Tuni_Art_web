<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['phoneNb'], message: 'There is already an account with this phone number')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $uid = null;

    #[ORM\Column(length: 128)]
    #[Assert\NotBlank(message: 'First Name is required')]
    #[Assert\Regex(pattern: "/^[a-zA-Z]+$/", message: "First name must only contain letters")]
    private ?string $fname = null;

    #[ORM\Column(length: 128)]
    #[Assert\NotBlank(message: 'Last Name is required')]
    #[Assert\Regex(pattern: "/^[a-zA-Z]+$/", message: "Last name must only contain letters")]
    private ?string $lname = null;

    #[ORM\Column(length: 512)]
    #[Assert\NotBlank(message: 'Email is required')]
    #[Assert\Email(message: 'Email {{ value }} must be valid')]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Gender is required')]
    private ?bool $gender = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Phone Number is required')]
    #[Assert\Length(min: 8, max: 8, exactMessage: "Phone number must be exactly 8 digits long")]
    private ?int $phoneNb = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $profilePic;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'Date of Birth is required')]
    #[Assert\LessThan(propertyPath: 'today', message: 'Date of Birth must be before today')]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(length: 512)]
    // #[Assert\NotBlank(message: 'Password is required')]
    // #[Assert\Length(min: 8, max: 255, exactMessage: 'Password must be between 8 and 255 characters long')]
    // #[Assert\Regex(pattern: "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/", message: "Password must contain at least one uppercase letter, one lowercase letter, and one number")]
    private ?string $password = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $verificationCode;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $biography;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $portfolio;

    #[ORM\Column(length: 512)]
    private ?string $role = null;

    #[ORM\Column(nullable: true)]
    private ?bool $status;

    #[ORM\Column]
    private ?int $profileviews = 0;

    private $roles = [];

    #[ORM\OneToMany(mappedBy: 'artist', targetEntity: Art::class)]
    private Collection $art;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'followers')]
    private Collection $followers;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Auction::class)]
    private Collection $auctions;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Cart::class)]
    private Collection $carts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Event::class)]
    private Collection $events;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Order::class)]
    private Collection $orders;

    public function __construct()
    {
        $this->art = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->auctions = new ArrayCollection();
        $this->carts = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    // #[ORM\ManyToMany(targetEntity: "User", mappedBy: "following")]
    // private Collection $followers;

    /**
     * Constructor
     */
    // public function __construct()
    // {
    //     $this->followers = new ArrayCollection();
    // }

    public function getToday(): \DateTimeInterface
    {
        return new \DateTimeImmutable();
    }

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function getFname(): ?string
    {
        return $this->fname;
    }

    public function setFname(string $fname): static
    {
        $this->fname = $fname;

        return $this;
    }

    public function getLname(): ?string
    {
        return $this->lname;
    }

    public function setLname(string $lname): static
    {
        $this->lname = $lname;

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

    public function isGender(): ?bool
    {
        return $this->gender;
    }

    public function setGender(bool $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPhoneNb(): ?int
    {
        return $this->phoneNb;
    }

    public function setPhoneNb(int $phoneNb): static
    {
        $this->phoneNb = $phoneNb;

        return $this;
    }

    public function getProfilePic(): ?string
    {
        return $this->profilePic;
    }

    public function setProfilePic(?string $profilePic): static
    {
        $this->profilePic = $profilePic;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getVerificationCode(): ?string
    {
        return $this->verificationCode;
    }

    public function setVerificationCode(?string $verificationCode): static
    {
        $this->verificationCode = $verificationCode;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): static
    {
        $this->biography = $biography;

        return $this;
    }

    public function getPortfolio(): ?string
    {
        return $this->portfolio;
    }

    public function setPortfolio(?string $portfolio): static
    {
        $this->portfolio = $portfolio;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getProfileviews(): ?int
    {
        return $this->profileviews;
    }

    public function setProfileviews(int $profileviews): static
    {
        $this->profileviews = $profileviews;

        return $this;
    }

    // /**
    //  * @return Collection<int, User>
    //  */
    // public function getFollowers(): Collection
    // {
    //     return $this->followers;
    // }

    // public function addFollower(User $follower): static
    // {
    //     if (!$this->followers->contains($follower)) {
    //         $this->followers->add($follower);
    //         #$follower->addFollowing($this);
    //     }

    //     return $this;
    // }

    // public function removeFollower(User $follower): static
    // {
    //     if ($this->followers->removeElement($follower)) {
    //         #$follower->removeFollowing($this);
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, Art>
     */
    public function getArt(): Collection
    {
        return $this->art;
    }

    public function addArt(Art $art): static
    {
        if (!$this->art->contains($art)) {
            $this->art->add($art);
            $art->setArtist($this);
        }

        return $this;
    }

    public function removeArt(Art $art): static
    {
        if ($this->art->removeElement($art)) {
            // set the owning side to null (unless already changed)
            if ($art->getArtist() === $this) {
                $art->setArtist(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(self $follower): static
    {
        if (!$this->followers->contains($follower)) {
            $this->followers->add($follower);
        }

        return $this;
    }

    public function removeFollower(self $follower): static
    {
        $this->followers->removeElement($follower);

        return $this;
    }

    /**
     * @return Collection<int, Auction>
     */
    public function getAuctions(): Collection
    {
        return $this->auctions;
    }

    public function addAuction(Auction $auction): static
    {
        if (!$this->auctions->contains($auction)) {
            $this->auctions->add($auction);
            $auction->setUser($this);
        }

        return $this;
    }

    public function removeAuction(Auction $auction): static
    {
        if ($this->auctions->removeElement($auction)) {
            // set the owning side to null (unless already changed)
            if ($auction->getUser() === $this) {
                $auction->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cart>
     */
    public function getCartss(): Collection
    {
        return $this->carts;
    }

    public function addCartss(Cart $cartss): static
    {
        if (!$this->carts->contains($cartss)) {
            $this->carts->add($cartss);
            $cartss->setUser($this);
        }

        return $this;
    }

    public function removeCartss(Cart $cartss): static
    {
        if ($this->carts->removeElement($cartss)) {
            // set the owning side to null (unless already changed)
            if ($cartss->getUser() === $this) {
                $cartss->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setUser($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getUser() === $this) {
                $event->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

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
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
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
    public function setRoles(array $roles): void
    {
        $this->role = $roles;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
