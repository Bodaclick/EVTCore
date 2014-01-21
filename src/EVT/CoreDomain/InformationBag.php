<?php

namespace EVT\CoreDomain;

/**
 * InformationBag
 *
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright Bodaclick S.A
 */
class InformationBag implements \IteratorAggregate, \Countable
{
    protected $parameters = [];

    public function __construct(array $parameters = null)
    {
        if (null === $parameters) {
            $parameters = [];
        }
        $this->parameters = $parameters;
    }

    public function set($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    public function get($key)
    {
        return $this->parameters[$key];
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->parameters);
    }

    public function count()
    {
        return count($this->parameters);
    }
}
