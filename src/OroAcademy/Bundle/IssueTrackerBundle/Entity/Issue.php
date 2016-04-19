<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use OroAcademy\Bundle\IssueTrackerBundle\Model\ExtendIssue;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="OroAcademy\Bundle\IssueTrackerBundle\Repository\IssueRepository")
 * @ORM\Table(
 *      name="oroacademy_issues",
 *      indexes={
 *          @ORM\Index(name="issue_code_idx",columns={"code"}),
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 * @Config(
 *      defaultValues={
 *          "tag"={
 *              "enabled"=true
 *          },
 *          "workflow"={
 *              "active_workflow"="issue_status_workflow",
 *              "show_step_in_grid"=false
 *          }
 *      }
 * )
 */
class Issue extends ExtendIssue
{
    const CODE_PREFIX = 'ORO-';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "identity"=true,
     *              "order"=10
     *          }
     *      }
     * )
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="summary")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=20
     *          }
     *      }
     * )
     */
    protected $summary;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="code")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=30
     *          }
     *      }
     * )
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(type="text", name="description")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=40
     *          }
     *      }
     * )
     */
    protected $description;

    /**
     * @var IssueType
     *
     * @ORM\ManyToOne(targetEntity="IssueType", inversedBy="issues")
     * @ORM\JoinColumn(name="issue_type_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=50
     *          }
     *      }
     * )
     */
    protected $type;

    /**
     * @var IssuePriority
     *
     * @ORM\ManyToOne(targetEntity="IssuePriority", inversedBy="issues")
     * @ORM\JoinColumn(name="issue_priority_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=60
     *          }
     *      }
     * )
     */
    protected $priority;

    /**
     * @var IssueResolution
     *
     * @ORM\ManyToOne(targetEntity="IssueResolution", inversedBy="issues")
     * @ORM\JoinColumn(name="issue_resolution_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=70
     *          }
     *      }
     * )
     */
    protected $resolution;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="reporter_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=80
     *          }
     *      }
     * )
     */
    protected $reporter;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="assignee_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=90
     *          }
     *      }
     * )
     */
    protected $assignee;

    /**
     * @var User[]
     *
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable(
     *     name="issues_collaborators",
     *     joinColumns={@ORM\JoinColumn(name="issue_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $collaborators;

    /**
     * @var Issue
     *
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $parent;

    /**
     * @var Issue
     *
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="parent")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $children;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="created_at")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=130
     *          }
     *      }
     * )
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="updated_at")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=140
     *          }
     *      }
     * )
     */
    protected $updatedAt;

    /**
     * @var ArrayCollection $tags
     * @ConfigField(
     *      defaultValues={
     *          "merge"={
     *              "display"=true
     *          }
     *      }
     * )
     */
    protected $tags;

    /**
     * @var IssueStatus
     *
     * @ORM\ManyToOne(targetEntity="IssueStatus", inversedBy="issues")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id", nullable=false)
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=150
     *          }
     *      }
     * )
     */
    protected $status;

    /**
     * Issue constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setCreatedAt();
        $this->setUpdatedAt();
        $this->generateCode();
        $this->children = new ArrayCollection();
        $this->collaborators = new ArrayCollection();
    }

    /**
     * @return $this
     */
    public function generateCode()
    {
        $this->code = uniqid(self::CODE_PREFIX);

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $createdAt = $createdAt ?: new \DateTime();
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $updatedAt = $updatedAt ?: new \DateTime();
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PreUpdate
     */
    public function refreshUpdatedAt()
    {
        $this->setUpdatedAt();
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     * @return $this
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return IssueType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param IssueType $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return IssuePriority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param IssuePriority $priority
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return IssueResolution
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @param IssueResolution $resolution
     * @return $this
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * @return User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * @param User $reporter
     * @return $this
     */
    public function setReporter(User $reporter = null)
    {
        $this->reporter = $reporter;
        $this->addCollaborator($reporter);

        return $this;
    }

    /**
     * @return User
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * @param User $assignee
     * @return $this
     */
    public function setAssignee(User $assignee = null)
    {
        $this->assignee = $assignee;
        $this->addCollaborator($assignee);

        return $this;
    }

    /**
     * @return Issue
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Issue $parent
     * @return $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Issue
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Issue $issue
     * @return $this
     */
    public function addChild(Issue $issue)
    {
        $this->children->add($issue);

        return $this;
    }

    /**
     * @param Issue $issue
     * @return $this
     */
    public function removeChild(Issue $issue)
    {
        $this->children->removeElement($issue);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTags()
    {
        if (null === $this->tags) {
            $this->tags = new ArrayCollection();
        }

        return $this->tags;
    }

    /**
     * @param $tags
     * @return Issue
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return User[]
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }

    /**
     * @param User[] $collaborators
     * @return $this
     */
    public function setCollaborators($collaborators)
    {
        $this->collaborators = $collaborators;

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addCollaborator(User $user)
    {
        if (!$this->collaborators->contains($user)) {
            $this->collaborators->add($user);
        }

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function removeCollaborator(User $user)
    {
        $this->collaborators->removeElement($user);

        return $this;
    }
}
