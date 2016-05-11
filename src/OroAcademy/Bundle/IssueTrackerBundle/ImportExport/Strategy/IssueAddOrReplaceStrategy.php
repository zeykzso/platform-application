<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\ImportExport\Strategy;

use Doctrine\ORM\EntityManagerInterface;
use Oro\Bundle\ImportExportBundle\Strategy\Import\ConfigurableAddOrReplaceStrategy;
use OroAcademy\Bundle\IssueTrackerBundle\Entity\IssueStatus;
use OroAcademy\Bundle\IssueTrackerBundle\Entity\IssueType;
use OroAcademy\Bundle\IssueTrackerBundle\Form\Handler\IssueHandler;

class IssueAddOrReplaceStrategy extends ConfigurableAddOrReplaceStrategy
{
    /** @var  EntityManagerInterface */
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }
    
    /**
     * @inheritdoc
     */
    public function process($entity)
    {
        if (!$entity->getStatus()) {
            $openStatus = $this->em->getRepository('OroAcademyIssueTrackerBundle:IssueStatus')
                ->findOneByName(IssueStatus::STATUS_OPEN);
            $entity->setStatus($openStatus);
        }
        if (!$entity->getType()) {
            $taskType = $this->em->getRepository('OroAcademyIssueTrackerBundle:IssueType')
                ->findOneByName(IssueType::TYPE_TASK);
            $entity->setType($taskType);
        }

        return parent::process($entity);
    }
}
