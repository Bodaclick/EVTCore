<?php
namespace EVT\CoreDomain\Provider;

use EVT\CoreDomain\RepositoryInterface;

/**
 * ProviderRepository
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
interface ProviderRepository extends RepositoryInterface
{
    public function find($domain);
}
