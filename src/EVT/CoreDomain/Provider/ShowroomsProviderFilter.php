<?php

namespace EVT\CoreDomain\Provider;

/**
 * ShowroomsProviderFilter
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class ShowroomsProviderFilter extends \FilterIterator
{
    private $providerFilter;

    public function __construct(\Iterator $iterator, Provider $filter)
    {
        parent::__construct($iterator);
        $this->providerFilter = $filter;
    }

    public function accept()
    {
        $showroom = $this->getInnerIterator()->current();
        if ($showroom->belongsToProvider($this->providerFilter)) {
            return true;
        }
        return false;
    }
}
