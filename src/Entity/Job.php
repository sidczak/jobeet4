<?php

namespace App\Entity;
use Gedmo\Sluggable\Util as Sluggable;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 */
class Job
{
    
    /**
     * @JMS\Expose()
     * @JMS\Type("int")
     */
    private $id;

    /**
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    private $type;

    /**
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    private $company;

    private $logo;

    /**
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    private $url;

    /**
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    private $position;

    /**
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    private $location;

    /**
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    private $description;

    /**
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    private $howToApply;

    private $token;

    private $public;

    private $activated;

    private $email;

    /**
     * @JMS\Expose()
     * @JMS\Type("DateTime")
     */
    private $expiresAt;

    private $createdAt;

    private $updatedAt;

    private $category;

    public const FULL_TIME_TYPE = 'full-time';
    public const PART_TIME_TYPE = 'part-time';
    public const FREELANCE_TYPE = 'freelance';

    public const TYPES = [
        self::FULL_TIME_TYPE,
        self::PART_TIME_TYPE,
        self::FREELANCE_TYPE,
    ];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function setLogo($logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("logo_path")
     *
     * @return string|null
     */
    public function getLogoPath()
    {
        return $this->getLogo() ? 'uploads/jobs/' . $this->getLogo()->getFilename() : null;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getHowToApply(): ?string
    {
        return $this->howToApply;
    }

    public function setHowToApply(string $howToApply): self
    {
        $this->howToApply = $howToApply;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function getActivated(): ?bool
    {
        return $this->activated;
    }

    public function setActivated(bool $activated): self
    {
        $this->activated = $activated;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTimeInterface $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("category_name")
     *
     * @return string
     */
    public function getCategoryName()
    {
        return $this->getCategory()->getName();
    }
    
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        
        if (!$this->expiresAt) {
            $this->expiresAt = (clone $this->createdAt)->modify('+30 days');
        }
    }
    
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getCompanySlug()
    {
        return Sluggable\Urlizer::urlize($this->company, '-');
    }

    public function getPositionSlug()
    {
        return Sluggable\Urlizer::urlize($this->position, '-');
    }
 
    public function getLocationSlug()
    {
        return Sluggable\Urlizer::urlize($this->location, '-');
    }
}
