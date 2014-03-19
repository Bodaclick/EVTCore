<?php

namespace EVT\CoreDomainBundle\Factory;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use EVT\CoreDomainBundle\Model\Paginator;

/**
 * PaginatorFactory
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class PaginatorFactory
{
    public static function create(SlidingPagination $slidingPagination, $arrayDomLeads)
    {
        return new Paginator($slidingPagination, $arrayDomLeads);
    }
}
