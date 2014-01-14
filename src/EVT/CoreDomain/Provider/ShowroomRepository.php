<?php
namespace EVT\CoreDomain\Provider;

use EVT\CoreDomain\RepositoryInterface;

/**
 * ShowroomRepository
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
interface ShowroomRepository extends RepositoryInterface
{
    public function find($domain);
}