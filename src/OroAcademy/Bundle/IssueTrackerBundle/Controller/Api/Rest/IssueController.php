<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\Controller\Api\Rest;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;

/**
 * @RouteResource("issue")
 * @NamePrefix("oro_academy_api_")
 */
class IssueController extends RestController
{
    /**
     * @Acl(
     *      id="oro_academy.issue_delete",
     *      type="entity",
     *      class="OroAcademyIssueTrackerBundle:Issue",
     *      permission="DELETE"
     * )
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteRequest($id);
    }

    public function getForm()
    {
    }

    public function getFormHandler()
    {
    }

    public function getManager()
    {
        return $this->get('oro_academy.issue_manager.api');
    }
}
