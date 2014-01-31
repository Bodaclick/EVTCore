<?php
namespace EVT\CoreDomain\Provider;

use EVT\CoreDomain\RepositoryInterface;

/**
 * VerticalRepository
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
interface VerticalRepository extends RepositoryInterface
{
    public function findVertical($domain);
}
