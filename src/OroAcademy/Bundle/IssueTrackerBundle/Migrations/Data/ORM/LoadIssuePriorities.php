<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OroAcademy\Bundle\IssueTrackerBundle\Entity\IssuePriority;

class LoadIssuePriorities implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @inheritdoc
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        if (count($manager->getRepository('OroAcademyIssueTrackerBundle:IssuePriority')->findAll()) > 0) {
            return;
        }

        foreach (IssuePriority::getPrioritiesByOrder() as $index => $priorityName) {
            $priority = new IssuePriority($priorityName);
            $priority->setOrder($index + 1);
            $manager->persist($priority);
        }

        $manager->flush();
    }
}
