<?php

namespace EVT\CoreDomain;

/**
 * RepositoryInterface
 *
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright Bodaclick S.A
 */
interface RepositoryInterface
{
    public function save($object);

    public function delete($object);

    public function update($object);
}
