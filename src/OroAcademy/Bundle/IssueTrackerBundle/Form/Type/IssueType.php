<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('summary')
            ->add('description')
            ->add('priority', 'translatable_entity', [
                'label' => 'oro_academy.entity.issue.priority.label',
                'class' => 'OroAcademyIssueTrackerBundle:IssuePriority',
                'property' => 'name',
                'required' => true
            ])
            ->add('assignee', 'entity', [
                'class' => 'Oro\Bundle\UserBundle\Entity\User',
                'required' => false
            ])
            ->add(
                'tags',
                'oro_tag_select',
                array(
                    'label' => 'oro.tag.entity_plural_label'
                )
            );

        $issue = $options['data'];
        if (!$issue->getParent()) {
            $builder->add('type', 'translatable_entity', [
                'label' => 'oro_academy.entity.issue.type.label',
                'class' => 'OroAcademyIssueTrackerBundle:IssueType',
                'property' => 'name',
                'required' => true
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'OroAcademy\Bundle\IssueTrackerbundle\Entity\Issue'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'oro_academy_issue';
    }
}
