<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="fname", type="string", length=128, nullable=false)
     */
    private $fname;

    /**
     * @var string
     *
     * @ORM\Column(name="lname", type="string", length=128, nullable=false)
     */
    private $lname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=512, nullable=false)
     */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(name="gender", type="boolean", nullable=false)
     */
    private $gender;

    /**
     * @var int
     *
     * @ORM\Column(name="phone_nb", type="integer", nullable=false)
     */
    private $phoneNb;

    /**
     * @var string|null
     *
     * @ORM\Column(name="profile_pic", type="string", length=512, nullable=true)
     */
    private $profilePic;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="date", nullable=false)
     */
    private $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=512, nullable=false)
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="verification_code", type="string", length=512, nullable=true)
     */
    private $verificationCode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="biography", type="string", length=512, nullable=true)
     */
    private $biography;

    /**
     * @var string|null
     *
     * @ORM\Column(name="portfolio", type="string", length=512, nullable=true)
     */
    private $portfolio;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=512, nullable=false)
     */
    private $role;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="profileViews", type="integer", nullable=false)
     */
    private $profileviews = '0';

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="following")
     */
    private $follower = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->follower = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getFollower(): Collection
    {
        return $this->follower;
    }

    public function addFollower(User $follower): static
    {
        if (!$this->follower->contains($follower)) {
            $this->follower->add($follower);
            $follower->addFollowing($this);
        }

        return $this;
    }

    public function removeFollower(User $follower): static
    {
        if ($this->follower->removeElement($follower)) {
            $follower->removeFollowing($this);
        }

        return $this;
    }

}
