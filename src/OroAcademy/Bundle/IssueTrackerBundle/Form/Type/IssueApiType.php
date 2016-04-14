<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;

use Oro\Bundle\SoapBundle\Form\EventListener\PatchSubscriber;

class IssueApiType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new PatchSubscriber());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'csrf_protection'      => false,
                'cascade_validation'   => false,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'oro_academy_issue';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'oro_academy_issue_api';
    }
}
