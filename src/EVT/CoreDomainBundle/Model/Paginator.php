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
    private $currentPageNumber;
    private $numItemsPerPage;
    private $totalCount;
    private $totalPages;
    private $items = [];

    public function __construct(SlidingPagination $slidingPagination, $arrayDomLeads)
    {
        $this->currentPageNumber = $slidingPagination->getCurrentPageNumber();
        $this->numItemsPerPage = $slidingPagination->getItemNumberPerPage();
        $this->totalCount = $slidingPagination->getTotalItemCount();
        $this->totalPages = ceil($this->getTotalCount() / $this->numItemsPerPage);
        $this->items = $arrayDomLeads;
    }

    /**
     * @return int
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * @return int
     */
    public function getCurrentPageNumber()
    {
        return $this->currentPageNumber;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getNumItemsPerPage()
    {
        return $this->numItemsPerPage;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }
}
