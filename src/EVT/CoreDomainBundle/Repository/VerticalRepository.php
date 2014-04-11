<?php

namespace EVT\CoreDomainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use EVT\CoreDomain\Provider\Provider;
use EVT\CoreDomain\Provider\Showroom;
use EVT\CoreDomain\Provider\Vertical;
use EVT\CoreDomain\Provider\VerticalRepositoryInterface as DomainRepository;
use EVT\CoreDomainBundle\Mapping\ShowroomMapping;
use EVT\CoreDomainBundle\Mapping\VerticalMapping;

class VerticalRepository extends EntityRepository implements DomainRepository
{
    private $mapping;
    private $showroomMapper;
    private $userRepo;

    public function save($vertical)
    {
    }

    public function delete($vertical)
    {
    }

    public function update($vertical)
    {
    }

    public function findAllWithCanview($username)
    {
        if (empty($username)) {
            return null;
        }

        $verticalsResult = $this->_em->createQuery(
            "SELECT v
            FROM EVTCoreDomainBundle:Vertical v
            WHERE v.domain IN (
                SELECT DISTINCT v1.domain
                FROM EVTCoreDomainBundle:Showroom s
                    JOIN s.provider p
                    JOIN p.genericUser u
                    JOIN s.vertical v1
                WHERE u.username = :username
                GROUP BY v1.domain
            )
            ORDER BY v.lang ASC, v.domain ASC"
        )
            ->setParameter("username", $username)
            ->getResult();

        if (sizeof($verticalsResult) == 0 && null !== $this->userRepo->getEmployeeByUsername($username)) {
            $verticalsResult = $this->_em->createQuery(
                "SELECT v
                FROM EVTCoreDomainBundle:Vertical v
                ORDER BY v.lang ASC, v.domain ASC"
            )
                ->getResult();
        }

        foreach ($verticalsResult as $vertical) {
            $verticals[] = $this->mapping->mapEntityToDomain($vertical);
        }

        return $verticals;
    }

    public function findOneByDomain($domain)
    {
        if (!$vertical = parent::findOneByDomain($domain)) {
            return null;
        }
        return $this->mapping->mapEntityToDomain($vertical);
    }

    /**
     * @param Vertical $vertical
     * @param Provider $provider
     * @return Showroom
     */
    public function findShowroom(Vertical $vertical, Provider $provider)
    {
        $qb = $this->_em->createQuery(
            'SELECT s FROM EVTCoreDomainBundle:Showroom s WHERE s.provider = :provider AND s.vertical = :vertical'
        );
        $qb->setParameter('provider', $provider->getId());
        $qb->setParameter('vertical', $vertical->getDomain());
        if ($showroom = $qb->getOneOrNullResult()) {
            return $this->showroomMapper->mapEntityToDomain($showroom);
        }
    }

    public function setMapper(VerticalMapping $mapping)
    {
        $this->mapping = $mapping;
    }

    public function setShowroomMapper(ShowroomMapping $mapping)
    {
        $this->showroomMapper = $mapping;
    }

    public function setUserRepo($userRepo)
    {
        $this->userRepo = $userRepo;
    }
}
