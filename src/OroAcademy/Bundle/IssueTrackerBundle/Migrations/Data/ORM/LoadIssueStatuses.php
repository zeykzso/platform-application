<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OroAcademy\Bundle\IssueTrackerBundle\Entity\IssueStatus;

class LoadIssueStatuses implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @inheritdoc
     */
    public function getOrder()
    {
        return 4;
    }

    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        if (count($manager->getRepository('OroAcademyIssueTrackerBundle:IssueStatus')->findAll()) > 0) {
            return;
        }

        foreach (IssueStatus::getStatuses() as $index => $statusName) {
            $status = new IssueStatus($statusName);
            $status->setOrder($index + 1);
            $manager->persist($status);
        }

        $manager->flush();
    }
}
