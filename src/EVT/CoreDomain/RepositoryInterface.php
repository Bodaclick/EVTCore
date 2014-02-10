<?php

namespace EVT\CoreDomain;

/**
 * RepositoryInterface
 *
 * @author    Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
interface RepositoryInterface
{
    public function findAll();
    
    public function save($object);

    public function delete($object);
}
