<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\ImportExport\Serializer\Normalizer;

use Oro\Bundle\ImportExportBundle\Serializer\Normalizer\ConfigurableEntityNormalizer;
use OroAcademy\Bundle\IssueTrackerBundle\Entity\Issue;

class IssueNormalizer extends ConfigurableEntityNormalizer
{
    public function normalize($object, $format = null, array $context = [])
    {
        return parent::normalize($object, $format, $context);
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return parent::denormalize($data, $class, $format, $context);
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Issue;
    }

    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        return is_array($data) && $type == 'OroAcademy\Bundle\IssueTrackerBundle\Entity\Issue';
    }
}
