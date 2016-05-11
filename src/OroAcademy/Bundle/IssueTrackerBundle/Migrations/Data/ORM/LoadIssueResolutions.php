<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OroAcademy\Bundle\IssueTrackerBundle\Entity\IssueResolution;

class LoadIssueResolutions implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @inheritdoc
     */
    public function getOrder()
    {
        return 2;
    }

    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        if (count($manager->getRepository('OroAcademyIssueTrackerBundle:IssueResolution')->findAll()) > 0) {
            return;
        }

        foreach (IssueResolution::getResolutions() as $resolutionName) {
            $resolution = new IssueResolution($resolutionName);
            $manager->persist($resolution);
        }

        $manager->flush();
    }
}
