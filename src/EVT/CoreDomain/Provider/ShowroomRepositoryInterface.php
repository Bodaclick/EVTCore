<?php

namespace EVT\CoreDomain\Provider;

use EVT\CoreDomain\RepositoryInterface;

/**
 * ShowroomRepository
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
interface ShowroomRepositoryInterface extends RepositoryInterface
{
    public function find($id);
}
