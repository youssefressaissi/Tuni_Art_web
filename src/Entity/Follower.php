<?php

namespace App\Entity;

use App\Repository\FollowerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FollowerRepository::class)]
#[ORM\Table(name: '`followers`')]
#[ORM\UniqueConstraint(columns: ["follower_id", "following_id"])]
class Follower
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'follower_id', referencedColumnName: 'uid', nullable: false)]
    private $follower;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'following_id', referencedColumnName: 'uid', nullable: false)]
    private $following;

    public function getFollower(): ?User
    {
        return $this->follower;
    }

    public function setFollower(?User $follower): self
    {
        $this->follower = $follower;

        return $this;
    }

    public function getFollowing(): ?User
    {
        return $this->following;
    }

    public function setFollowing(?User $following): self
    {
        $this->following = $following;

        return $this;
    }
}
