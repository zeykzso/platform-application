<?php

namespace OroAcademy\Bundle\IssueTrackerBundle\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DashboardController extends Controller
{
    /**
     * @Route(
     *      "/issues-by-status/chart/{widget}",
     *      name="oro_academy.dashboard.issues_by_status",
     *      requirements={"widget"="[\w-]+"}
     * )
     * @Template("OroAcademyIssueTrackerBundle:Dashboard:issuesByStatus.html.twig")
     */
    public function issuesByStatus($widget)
    {
        $issueData = $this->getDoctrine()
            ->getRepository('OroAcademyIssueTrackerBundle:Issue')
            ->getIssuesByStatusData();

        $translator = $this->get('translator');

        foreach ($issueData as &$item) {
            $item['statusName'] = $translator->trans($item['statusName']);
        }

        $widgetAttr = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig($widget);
        $widgetAttr['chartView'] = $this->get('oro_chart.view_builder')
            ->setArrayData($issueData)
            ->setOptions([
                'name' => 'bar_chart',
                'data_schema' => [
                    'label' => [
                        'field_name' => 'statusName',
                        'label' => 'oro_academy.dashboard.issues_by_status.status.label',
                        'type' => 'string'
                    ],
                    'value' => [
                        'field_name' => 'nrOfIssues',
                        'label' => 'oro_academy.dashboard.issues_by_status.number_of_issues.label',
                        'type' => 'number'
                    ]
                ],
            ])
            ->getView();

        return $widgetAttr;
    }
}
