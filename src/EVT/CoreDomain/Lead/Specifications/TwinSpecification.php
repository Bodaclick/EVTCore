<?php

namespace EVT\CoreDomain\Lead\Specifications;

use EVT\CoreDomain\Lead\LeadRepositoryInterface;
use EVT\CoreDomain\Lead\Lead;

/**
 * TwinSpecification
 *
 * Check if the created lead belongs to a twin creation (2 igual leads in less then 5")
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class TwinSpecification implements LeadSpecificationInterface
{
    const SECONDS_FOR_TWINS = 5;
    private $leadRepo;
    private $twin;

    public function __construct(LeadRepositoryInterface $leadRepo)
    {
        $this->leadRepo = $leadRepo;
        $this->twin = null;
    }

    public function isSatisfiedBy(Lead $lead)
    {
        $twinLeads = $this->leadRepo->findByShowroomEmailSeconds(
            $lead->getShowroom(),
            $lead->getEmail()->getEmail(),
            TwinSpecification::SECONDS_FOR_TWINS
        );

        if (count($twinLeads) > 0) {
            $this->twin = $twinLeads[0];
            return true;
        }

        return false;
    }

    /**
     *
     * @return Lead the twin lead or null
     */
    public function getTwin()
    {
        return $this->twin;
    }
}
