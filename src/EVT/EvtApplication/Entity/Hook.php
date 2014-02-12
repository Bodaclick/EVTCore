<?php

namespace EVT\EvtApplication\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hook
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class Hook
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $event;

    public function __construct($event, $url)
    {
        $this->event = $event;
        $this->url = $url;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
