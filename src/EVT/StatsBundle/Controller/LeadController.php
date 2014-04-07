<?php

namespace EVT\StatsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use FOS\RestBundle\Controller\Annotations\View;

/**
 * Class LeadController
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class LeadController extends Controller
{
    /**
     * Create a new lead stat from a hook
     *
     * @View(statusCode=200)
     */
    public function getLeadsAction(Request $request)
    {
        $from_date = $request->query->get('from_date', '2010-01-01');
        $to_date = $request->query->get('to_date', '2010-01-01');
        $statsLeadsRepo = $this->get('doctrine.orm.stats_entity_manager')->getRepository('EVTStatsBundle:Lead');
        return $statsLeadsRepo->findBetweenDates($from_date, $to_date);
    }

    /**
     * Create a new lead stat from a hook
     *
     * @View(statusCode=202)
     */
    public function postLeadAction(Request $request)
    {
        $data = [];
        $content = $request->getContent();
        if (!empty($content)) {
            $data = json_decode($content, true);
        }
        if (!isset($data['showroom'])) {
            throw new BadRequestHttpException('showroom not found');
        }

        $statsLeadsRepo = $this->get('doctrine.orm.stats_entity_manager')->getRepository('EVTStatsBundle:Lead');

        $statsLeadsRepo->add(
            $data['created_at'],
            $data['showroom']['vertical']['timezone'],
            $data['showroom']['vertical']['domain'],
            $data['showroom']['provider']['id'],
            $data['showroom']['id']
        );

        return [];
    }
}
