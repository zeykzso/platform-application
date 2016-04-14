<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Oro\Bundle\NoteBundle\Entity\Note;
use OroAcademy\Bundle\IssueTrackerBundle\Entity\Issue;

class AddNoteSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'preUpdate',
            'prePersist'
        );
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->preUpdate($args);
    }


    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Note) {
            return;
        }

        $metadata = $args->getEntityManager()->getClassMetadata(get_class($entity));
        foreach ($metadata->associationMappings as $mappingKey => $mapping) {
            if (substr($mappingKey, 0, 5) == 'issue') {
                $getter = 'get' . str_replace('_', '', $mappingKey);
                $issue = $entity->$getter();
                if ($issue instanceof Issue) {
                    $issue->refreshUpdatedAt();
                }
                break;
            }
        }

    }
}
