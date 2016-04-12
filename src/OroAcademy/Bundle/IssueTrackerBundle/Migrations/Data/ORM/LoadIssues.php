<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OroAcademy\Bundle\IssueTrackerBundle\Entity\Issue;
use OroAcademy\Bundle\IssueTrackerBundle\Entity\IssueStatus;
use OroAcademy\Bundle\IssueTrackerBundle\Entity\IssueType;

class LoadIssues implements FixtureInterface, OrderedFixtureInterface
{
    const NR_OF_ISSUES = 20;

    protected static $dummyData = [
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
        'Proin tellus elit, cursus id leo vel, varius posuere orci.',
        'Nunc malesuada lorem vitae magna molestie interdum.',
        'Nunc vehicula vestibulum odio vitae ullamcorper.',
        'Fusce vitae maximus dui. Suspendisse et dictum sapien',
        'non dapibus justo. Aliquam finibus iaculis ornare.',
        'Proin finibus gravida dignissim. Quisque mattis quis nulla quis mollis',
        'Phasellus facilisis a ligula eget lobortis.',
        'Aliquam ut ex vel purus semper pellentesque.',
        'Maecenas gravida cursus augue in finibus.',
        'Cras accumsan venenatis tortor ut malesuada.',
        'Aenean tellus metus, ornare et ultrices quis, eleifend non nibh.',
        'Nulla non scelerisque mauris, sed sagittis ligula.'
    ];

    /**
     * @inheritdoc
     */
    public function getOrder()
    {
        return 5;
    }

    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        $priorities = $manager->getRepository('OroAcademyIssueTrackerBundle:IssuePriority')->findAll();
        $resolutions = $manager->getRepository('OroAcademyIssueTrackerBundle:IssueResolution')->findAll();
        $types = $manager->getRepository('OroAcademyIssueTrackerBundle:IssueType')->findAll();
        $subTaskType = $manager->getRepository('OroAcademyIssueTrackerBundle:IssueType')
            ->findOneByName(IssueType::TYPE_SUB_TASK);
        $users = $manager->getRepository('OroUserBundle:User')->findAll();
        $statuses = $manager->getRepository('OroAcademyIssueTrackerBundle:IssueStatus')->findAll();

        for ($i = 1; $i <= self::NR_OF_ISSUES; $i++) {
            $issue = new Issue();
            $issue->setSummary($this->generateSummary());
            $issue->setDescription($this->generateDescription());
            $issue->setType($types[array_rand($types)]);
            $issue->setPriority($priorities[array_rand($priorities)]);
            $issue->setStatus($statuses[array_rand($statuses)]);
            if (in_array($issue->getStatus()->getName(), [IssueStatus::STATUS_RESOLVED, IssueStatus::STATUS_CLOSED])) {
                $issue->setResolution($resolutions[array_rand($resolutions)]);
            }
            $issue->setReporter($users[array_rand($users)]);
            $issue->setAssignee($users[array_rand($users)]);

            if ($issue->getType()->getName() == IssueType::TYPE_STORY) {
                // Add subtasks to story type issue
                for ($j = 1; $j <= rand(1, 4); $j++) {
                    $subIssue = new Issue();
                    $subIssue->setSummary($this->generateSummary());
                    $subIssue->setDescription($this->generateDescription());
                    $subIssue->setType($subTaskType);
                    $subIssue->setPriority($priorities[array_rand($priorities)]);
                    $subIssue->setResolution($resolutions[array_rand($resolutions)]);
                    $subIssue->setStatus($statuses[array_rand($statuses)]);
                    $subIssue->setReporter($issue->getReporter());
                    $subIssue->setAssignee($issue->getAssignee());
                    $subIssue->setParent($issue);
                    $manager->persist($subIssue);
                }
            }
            $manager->persist($issue);
        }

        $manager->flush();
    }

    /**
     * @return string
     */
    protected function generateSummary()
    {
        return substr(self::$dummyData[array_rand(self::$dummyData)], 0, 25);
    }

    /**
     * @return string
     */
    protected function generateDescription()
    {
        return self::$dummyData[array_rand(self::$dummyData)];
    }
}
