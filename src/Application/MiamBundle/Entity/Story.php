<?php

namespace Application\MiamBundle\Entity;
use Symfony\Components\Validator\Mapping\ClassMetadata;
use Symfony\Components\Validator\Constraints;

/**
 * @Entity(repositoryClass="Application\MiamBundle\Entity\StoryRepository")
 * @Table(name="miam_story")
 * @HasLifecycleCallbacks
 */
class Story
{
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Constraints\NotBlank());
        $metadata->addPropertyConstraint('name', new Constraints\MinLength(3));
    }

    /**
     * @ManyToOne(targetEntity="Project", inversedBy="stories")
     * @JoinColumn(name="project_id", nullable=false)
     */
    protected $project;

    /**
     * @ManyToOne(targetEntity="Sprint", inversedBy="stories")
     * @JoinColumn(name="sprint_id", nullable=true)
     */
    protected $sprint;   

    /**
     * @Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @Column(name="priority", type="integer")
     */
    protected $priority;

    /**
     * @Column(name="body", type="text", nullable=true)
     */
    protected $body;

    /**
     * @Column(name="points", type="text", nullable=true)
     */
    protected $points;

    /**
     * @Column(name="status", type="integer") 
     */
    protected $status;

    const STATUS_CREATED = 10;
    const STATUS_PENDING = 20;
    const STATUS_TODO = 30;
    const STATUS_WIP = 40;
    const STATUS_FINISHED = 50;
    const STATUS_DELETED = -10;

    /**
     * Story domain
     *
     * @Column(name="domain", type="integer") 
     */
    protected $domain = null;

    const DOMAIN_PROD = 10;
    const DOMAIN_DA = 20;
    const DOMAIN_GDP = 30;
    const DOMAIN_RD = 40;
    const DOMAIN_ADMIN = 50;
    const DOMAIN_INTERNAL = 60;

    /**
     * @OneToMany(targetEntity="TimelineEntry", mappedBy="story")
     */
    protected $timelineEntries;

    /**
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    const DEFAULT_NAME = '(story sans nom)';

    public function __construct()
    {
        $this->status    = self::STATUS_CREATED;
    }

    /**
     * Get domain
     * @return int
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set domain
     * @param  int
     * @return null
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public static function getDomains()
    {
        return array(
            self::DOMAIN_PROD => 'Prod',
            self::DOMAIN_DA => 'DA',
            self::DOMAIN_GDP => 'Gestion de projet',
            self::DOMAIN_RD => 'R&D',
            self::DOMAIN_ADMIN => 'Administratif',
            self::DOMAIN_INTERNAL => 'Interne'
        );
    }

    public function renderDomain()
    {
        $domains = self::getDomains();
        return isset($domains[$this->getDomain()]) ? $domains[$this->getDomain()] : '';
    }

    public function isDomain($domain)
    {
        return $this->getDomain() == $domain;
    }

    /**
     * getCreatedAt 
     * 
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * getUpdatedAt 
     * 
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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

    public function setPriority($value)
    {
        $this->priority = $value;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set body
     */
    public function setBody($value)
    {
        $this->body = $value;
    }

    /**
     * Get body
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get id
     */
    public function getId()
    {
        return $this->id;
    }

    public function setPoints($points)
    {
        $this->points = $points;
    }

    public function getPoints()
    {
        return $this->points;
    }

    public function setSprint(Sprint $sprint = null)
    {
        $this->sprint = $sprint;
    }

    public function getSprint()
    {  
        return $this->sprint;
    }

    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function isStatus($status)
    {
        return $this->status == $status;
    }

    public function isScheduled()
    {
        return $this->getStatus() > self::STATUS_CREATED;
    }

    public function isFinished()
    {
        return $this->getStatus() === self::STATUS_FINISHED;
    }

    public function isDeleted()
    {
        return $this->getStatus() === self::STATUS_DELETED;
    }

    public function setStatus($status)
    {
        if(!in_array($status, array_keys($this->getStatuses())))
        {
            throw new \InvalidArgumentException(sprintf('%s is not a valid story status like %s', $status, implode(', ', array_keys($this->getStatuses()))));
        }

        if($status >= self::STATUS_PENDING && !$this->getPoints())
        {
            throw new \LogicException('Story '.$this.' can not be scheduled because it has no points');
        }

        $this->status = $status;
    }

    public static function getStatuses()
    {
        return array(
            self::STATUS_CREATED => 'created',
            self::STATUS_PENDING => 'pending',
            self::STATUS_TODO => 'todo',
            self::STATUS_WIP => 'work in progress',
            self::STATUS_FINISHED => 'finished',
            self::STATUS_DELETED => 'deleted'
        );
    }

    public function renderStatus()
    {
        $statuses = self::getStatuses();

        return $statuses[$this->getStatus()];
    }

    /**
     * Tell if the given status code is valid 
     * 
     * @param string $status 
     * @return bool  true if the status is valid
     */
    public static function isValidStatus($status)
    {
        return in_array($status, array_keys(self::getStatuses()));
    }

    public static function getSprintStatuses()
    {
        $statuses = self::getStatuses();
        unset($statuses[self::STATUS_CREATED], $statuses[self::STATUS_DELETED]);
        return $statuses;
    } 

    public static function getExistStatuses()
    {
        $statuses = self::getStatuses();
        unset($statuses[self::STATUS_DELETED]);
        return $statuses;
    } 

    public function getStatusName()
    {
        $statuses = $this->getStatuses();

        return $statuses[$this->status];
    } 

    /**
     * Return an array version of a Story's properties
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'body' => $this->getBody(),
            'name' => $this->getName(),
            'created_at' => $this->getCreatedAt(),
            'priority' => $this->getPriority(),
            'points' => $this->getPoints(),
            'project' => $this->getProject() ? $this->getProject()->getId() : null
        );
    }

    public function __toString()
    {
        $name = $this->getName();
        return (isset($name) && null !== $name) ? $name : self::DEFAULT_NAME;
    }

    public function moveToTheEnd()
    {
        $this->setPriority(1000);
    }

    public function markAsDeleted()
    {
        $this->setStatus(self::STATUS_DELETED);
        $this->setSprint();
    }
}
