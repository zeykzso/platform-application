<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\Form\Handler;

use Oro\Bundle\SoapBundle\Form\Handler\ApiFormHandler;
use Oro\Bundle\UserBundle\Entity\User;
use OroAcademy\Bundle\IssueTrackerBundle\Entity\Issue;
use OroAcademy\Bundle\IssueTrackerBundle\Entity\IssueStatus;

class IssueHandler extends ApiFormHandler
{
    /**
     * @param Issue $issue
     * @param User $user
     * @return Issue
     */
    public function setDefaults(Issue $issue, User $user)
    {
        if (!$issue->getStatus()) {
            $issue->setStatus(
                $this->entityManager->getRepository('OroAcademyIssueTrackerBundle:IssueStatus')
                    ->findOneByName(IssueStatus::STATUS_OPEN)
            );
        }

        if (!$issue->getReporter()) {
            $issue->setReporter($user);
        }

        return $issue;
    }

    /**
     * @param Issue $issue
     * @param int $userId
     * @return Issue
     * @throws \Exception
     */
    public function setAssignee(Issue $issue, $userId)
    {
        $user = $this->entityManager->getRepository('OroUserBundle:User')->find($userId);
        if (!$userId) {
            throw new \Exception(sprintf('No user found with id: %s', $userId));
        }
        $issue->setAssignee($user);

        return $issue;
    }
}
