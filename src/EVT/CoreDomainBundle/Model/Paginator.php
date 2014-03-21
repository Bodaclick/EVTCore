<?php

namespace EVT\CoreDomainBundle\Model;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;

/**
 * Paginator
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */
class Paginator
{
    private $items = [];
    private $pagination = [];

    public function __construct(SlidingPagination $slidingPagination, $arrayDomLeads)
    {
        $this->pagination ["current_page"] = $slidingPagination->getCurrentPageNumber();
        $this->pagination ["items_per_page"] = $slidingPagination->getItemNumberPerPage();
        $this->pagination ["total_items"] = $slidingPagination->getTotalItemCount();
        $this->pagination ["total_pages"] = ceil($this->pagination ["total_items"] / $this->pagination ["items_per_page"]);
        $this->items = $arrayDomLeads;
    }

    /**
     * @return array
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }
}
