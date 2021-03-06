<?php

namespace Application\MiamBundle\Entity;
use Symfony\Components\Validator\Mapping\ClassMetadata;
use Symfony\Components\Validator\Constraints;

/**
 * @Entity(repositoryClass="Application\MiamBundle\Entity\ProjectRepository")
 * @Table(name="miam_project")
 * @HasLifecycleCallbacks
 */
class Project
{
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Constraints\NotBlank());
        $metadata->addPropertyConstraint('name', new Constraints\MinLength(3));
        $metadata->addPropertyConstraint('color', new Constraints\NotBlank());
        $metadata->addPropertyConstraint('color', new Constraints\Regex('/^#?[0-9A-F]{6}$/i'));
    }

    /**
    * @OneToMany(targetEntity="Story", mappedBy="project")
    */
    protected $stories;
    
    /**
     * @Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @Column(name="is_active", type="boolean", nullable=false)
     */
    protected $isActive = true;

    /**
     * @Column(name="color", type="string", length=7)
     */
    protected $color;

    /**
     * @Column(name="priority", type="integer", nullable=false)
     */
    protected $priority = 1;
    
    /**
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;
     
    /**
     * Set createdAt
     */
    public function setCreatedAt($value)
    {
        $this->createdAt = $value;
    }

    /**
     * Get createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set name
     */
    public function setName($value)
    {
        $this->name = $value;
    }

    /**
     * Get name
     */
    public function getName()
    {
        return $this->name;
    }

    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    public function isActive()
    {
        return $this->isActive;
    }
    
    public function setColor($color)
    {
        $this->color = $color;
    }
    
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Return an array version of a Story's properties
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'color' => $this->getColor(),
            'points' => $this->getPoints(),
            'created_at' => $this->getCreatedAt(),
        );
    }

    /** @PreUpdate */
    public function doStuffOnPreUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    /** @PrePersist */
    public function doStuffOnPrePersist()
    {
        $this->createdAt = $this->updatedAt = new \DateTime();
    }

    /**
     * Get id
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * getStories 
     * 
     * @return ArrayAccess
     */
    public function getStories()
    {
        return $this->stories;
    }

    public function setPriority($value)
    {
        $this->priority = $value;
    }

    public function getPriority()
    {
        return $this->priority;
    }
    
    public function __toString()
    {
        return $this->getName();
    }

    public function getTotalCreatedStoriesPoints()
    {
        $points = 0;
        foreach($this->getStories() as $story) {
            if($story->isStatus(Story::STATUS_CREATED)) {
                $points += $story->getPoints();
            }
        }

        return $points;
    }
    
}
