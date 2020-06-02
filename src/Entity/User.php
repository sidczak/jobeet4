<?php

namespace App\Entity;
use FOS\UserBundle\Model\User as BaseUser;

class User extends BaseUser
{
    protected $id;

    private $createdAt;

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }
}
