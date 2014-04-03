<?php

namespace EVT\StatsBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use EVT\StatsBundle\Entity\Lead;
use EVT\StatsBundle\Model\DateShifter;

/**
 * Class LeadRepository
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class LeadRepository extends EntityRepository
{
    public function add($dateTime, $timezone, $vertical, $provider, $showroom)
    {
        $em = $this->getEntityManager();
        // Try to update
        $result = $em->createQuery(
            "UPDATE EVTStatsBundle:Lead l
            SET l.number = l.number + 1
            WHERE l.date = :year
                AND l.showroomId = :showroom "
        )
            ->setParameter('year', DateShifter::dateShift(
                new \DateTime($dateTime, new \DateTimeZone('UTC')),
                $timezone
            ))
            ->setParameter('showroom', $showroom)
            ->execute();

        // If update fails -> insert
        if ($result !== 1) {
            $lead = new Lead(
                new \DateTime(DateShifter::dateShift(new \DateTime($dateTime, new \DateTimeZone('UTC')), $timezone)),
                $vertical,
                $provider,
                $showroom
            );
            $em->persist($lead);
            $em->flush();
        } else {
            // The update was ok, clear the EntityManager so it has to re-retrive the entity if needed
            $em->clear();
        }
    }

    public function findBetweenDates($from, $to)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT l FROM EVTStatsBundle:Lead l WHERE l.date BETWEEN :from AND :to'
            )
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->getResult();

        return $result;
    }

    public function getTotalForYear($year)
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT SUM(l.number) tot FROM EVTStatsBundle:Lead l WHERE l.date LIKE :year'
            )
            ->setParameter('year', $year . '-%')
            ->getOneOrNullResult();

        return $result['tot'];
    }
}
