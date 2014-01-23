<?php

namespace EVT\CoreDomain\Lead\Specifications;

use EVT\CoreDomain\Lead\Lead;

/**
 * LeadSpecificationInterface
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
interface LeadSpecificationInterface
{
    public function isSatisfiedBy(Lead $lead);
}
