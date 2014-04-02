<?php

namespace EVT\StatsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Lead
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="lead",
 *     indexes={
 *         @ORM\Index(name="vertical_idx", columns={"vertical"}),
 *         @ORM\Index(name="provider_idx", columns={"provider_id"}),
 *         @ORM\Index(name="showroom_idx", columns={"showroom_id"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="EVT\StatsBundle\Entity\Repository\LeadRepository")
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class Lead
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer $id
     */
    protected $id;

    /**
     * @ORM\Column(name="date", type="date", nullable=true)
     * @var DateTime $date
     */
    protected $date;

    /**
     * @ORM\Column(name="vertical", type="string", nullable=false)
     * @var $vertical
     */
    protected $vertical;

    /**
     * @ORM\Column(name="provider_id", type="integer", nullable=false)
     * @var providerId
     */
    protected $providerId;

    /**
     * @ORM\Column(name="showroom_id", type="integer", nullable=false)
     * @var showroomId
     */
    protected $showroomId;

    /**
     * @ORM\Column(name="number", type="integer", nullable=false)
     * @var number
     */
    protected $number = 1;

    public function __construct($date, $vertical, $providerId, $showroomId)
    {
        $this->date = $date;
        $this->vertical = $vertical;
        $this->providerId = $providerId;
        $this->showroomId = $showroomId;
    }

    public function getNumber()
    {
        return $this->number;
    }
}
