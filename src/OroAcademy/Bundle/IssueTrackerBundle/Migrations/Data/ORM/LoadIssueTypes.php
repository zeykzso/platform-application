<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OroAcademy\Bundle\IssueTrackerBundle\Entity\IssueType;

class LoadIssueTypes implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @inheritdoc
     */
    public function getOrder()
    {
        return 3;
    }

    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        if (count($manager->getRepository('OroAcademyIssueTrackerBundle:IssueType')->findAll()) > 0) {
            return;
        }

        foreach (IssueType::getTypes() as $typeName) {
            $type = new IssueType($typeName);
            $manager->persist($type);
        }

        $manager->flush();
    }
}
