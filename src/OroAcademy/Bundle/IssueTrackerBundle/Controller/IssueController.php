<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\Controller;

use OroAcademy\Bundle\IssueTrackerBundle\Entity\IssueStatus;
use OroAcademy\Bundle\IssueTrackerBundle\Entity\IssueType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

use OroAcademy\Bundle\IssueTrackerBundle\Entity\Issue;
use Oro\Bundle\NavigationBundle\Annotation\TitleTemplate;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/issue")
 */
class IssueController extends Controller
{
    /**
     * Show accounts list of partners
     *
     * @Route("/", name="oro_academy.issue_index")
     * @AclAncestor("oro_academy.issue_view")
     * @Template
     */
    public function indexAction()
    {
        return [
            'entity_class' => $this->container->getParameter('oro_academy.issue.entity.class'),
            'gridName' => 'issues-grid'
        ];
    }

    /**
     * @Route("/create", name="oro_academy.issue_create")
     * @Acl(
     *      id="oro_academy.issue_create",
     *      type="entity",
     *      permission="CREATE",
     *      class="OroAcademyIssueTrackerBundle:Issue"
     * )
     * @Template("OroAcademyIssueTrackerBundle:Issue:update.html.twig")
     * @TitleTemplate("Issue - create")
     * @param Request $request
     * @throws \Exception
     * @return array|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $issue = new Issue();
        if ($parentId = $request->query->get('parent')) {
            $em = $this->get('doctrine.orm.entity_manager');
            $parentIssue = $em->getRepository('OroAcademyIssueTrackerBundle:Issue')
                ->find($parentId);
            if (!$parentIssue || $parentIssue->getType()->getName() != IssueType::TYPE_STORY) {
                throw new \Exception(sprintf('Invalid parent id provided for subtask', $parentId));
            }
            $issue->setParent($parentIssue);
            $issue->setType(
                $em->getRepository('OroAcademyIssueTrackerBundle:IssueType')->findOneByName(IssueType::TYPE_SUB_TASK)
            );
        }
        return $this->update($issue, $request);
    }

    /**
     * @Route("/update/{id}", name="oro_academy.issue_update")
     * @Acl(
     *      id="oro_academy.issue_update",
     *      type="entity",
     *      permission="EDIT",
     *      class="OroAcademyIssueTrackerBundle:Issue"
     * )
     * @Template
     * @TitleTemplate("Issue - Update")
     * @param Issue $issue
     * @return array|RedirectResponse
     */
    public function updateAction(Issue $issue, Request $request)
    {
        return $this->update($issue, $request);
    }

    /**
     * @Route("/view/{id}", name="oro_academy.issue_view", requirements={"id"="\d+"})
     * @Acl(
     *      id="oro_academy.issue_view",
     *      type="entity",
     *      permission="VIEW",
     *      class="OroAcademyIssueTrackerBundle:Issue"
     * )
     * @Template()
     * @TitleTemplate("Issue - View")
     */
    public function viewAction(Issue $issue)
    {
        return [
            'entity' => $issue
        ];
    }

    /**
     * @param Issue $issue
     * @param Request $request
     * @return array|RedirectResponse
     */
    protected function update(Issue $issue, Request $request)
    {
        $form = $this->get('oro_academy.form.issue');
        $handler = $this->get('oro_academy.form.handler.issue');
        $handler->setDefaults($issue, $this->getUser());
        if ($userId = $request->query->get('entityId')) {
            $handler->setAssignee($issue, $userId);
        }

        $response = $this->get('oro_form.model.update_handler')->handleUpdate(
            $issue,
            $form,
            function (Issue $issue) {
                return [
                    'route' => 'oro_academy.issue_update',
                    'parameters' => array('id' => $issue->getId())
                ];
            },
            function (Issue $issue) {
                return [
                    'route' => 'oro_academy.issue_view',
                    'parameters' => array('id' => $issue->getId())
                ];
            },
            $this->get('translator')->trans('oro_academy.controller.issue_saved'),
            $this->get('oro_academy.form.handler.issue')
        );

        if (is_array($response)) {
            $em = $this->get('doctrine.orm.entity_manager');
            $response['priorities'] = $em->getRepository('OroAcademyIssueTrackerBundle:IssuePriority')->findAll();
            $response['assignees'] = $em->getRepository('OroUserBundle:User')->findAll();
        }

        return $response;
    }
}
