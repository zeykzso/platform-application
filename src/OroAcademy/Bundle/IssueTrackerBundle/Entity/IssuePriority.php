<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * @ORM\Entity()
 * @ORM\Table(name="oroacademy_issue_priorities")
 * @ORM\HasLifecycleCallbacks()
 * @Config()
 */
class IssuePriority
{
    const PRIORITY_TRIVIAL = 'trivial';
    const PRIORITY_MINOR = 'minor';
    const PRIORITY_MAJOR = 'major';
    const PRIORITY_CRITICAL = 'critical';
    const PRIORITY_BLOCKER = 'blocker';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="name")
     */
    protected $name;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", name="priority_order")
     */
    protected $order;

    /**
     * @var ArrayCollection|Issue[]
     *
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="priority")
     */
    protected $issues;

    /**
     * @return array
     */
    public static function getPrioritiesByOrder()
    {
        return [
            self::PRIORITY_TRIVIAL,
            self::PRIORITY_MINOR,
            self::PRIORITY_MAJOR,
            self::PRIORITY_CRITICAL,
            self::PRIORITY_BLOCKER
        ];
    }

    /**
     * IssuePriority constructor.
     *
     * @param string|null $name
     */
    public function __construct($name = null)
    {
        $this->name = $name;
        $this->issues = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return ArrayCollection|Issue[]
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * @param ArrayCollection|Issue[] $issues
     */
    public function setIssues($issues)
    {
        $this->issues = $issues;
    }

    /**
     * @param Issue $issue
     * @return $this
     */
    public function addIssue(Issue $issue)
    {
        $this->issues->add($issue);

        return $this;
    }

    /**
     * @param Issue $issue
     * @return $this
     */
    public function removeIssue(Issue $issue)
    {
        $this->issues->removeElement($issue);

        return $this;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
