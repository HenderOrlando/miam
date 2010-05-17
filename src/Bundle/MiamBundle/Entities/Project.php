<?php

namespace Bundle\MiamBundle\Entities;

/**
 * @Entity(repositoryClass="Bundle\MiamBundle\Entities\ProjectRepository")
 * @Table(name="miam_project")
 */
class Project
{
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
    protected $isActive;

    /**
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->isActive = true;
    }

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

    /**
     * Return an array version of a Story's properties
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'created_at' => $this->getCreatedAt(),
        );
    }

    /**
     * Get id
     */
    public function getId()
    {
        return $this->id;
    }


    public function __toString()
    {
        return $this->getName();
    }
    
}