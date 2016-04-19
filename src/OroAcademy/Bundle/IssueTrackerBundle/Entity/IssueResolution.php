<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * @ORM\Entity()
 * @ORM\Table(name="oroacademy_issue_resolutions")
 * @ORM\HasLifecycleCallbacks()
 * @Config()
 */
class IssueResolution
{
    const RESOLUTION_FIXED = 'fixed';
    const RESOLUTION_DUPLICATE = 'dulicate';
    const RESOLUTION_INCOMPLETE = 'incomplete';
    const RESOLUTION_CANNOT_REPRODUCE = 'cannot_reproduce';
    const RESOLUTION_DONE = 'done';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "identity"=true
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
     * @var ArrayCollection|Issue[]
     *
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="resolution")
     */
    protected $issues;

    /**
     * @return array
     */
    public static function getResolutions()
    {
        return [
            self::RESOLUTION_FIXED,
            self::RESOLUTION_DUPLICATE,
            self::RESOLUTION_INCOMPLETE,
            self::RESOLUTION_CANNOT_REPRODUCE,
            self::RESOLUTION_DONE,
        ];
    }

    /**
     * IssueResolution constructor.
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
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
