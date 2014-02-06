<?php

namespace EVT\ApiBundle\Factory;


use Doctrine\DBAL\DBALException;
use EVT\CoreDomain\Provider\ProviderRepositoryInterface;
use EVT\CoreDomain\Provider\VerticalRepositoryInterface;
use EVT\CoreDomain\Provider\ShowroomRepositoryInterface;

/**
 * Class ShowroomFactory
 * @copyright 2014 Bodaclick
 */
class ShowroomFactory
{
    protected $verticalRepo;
    protected $providerRepo;
    protected $showroomRepo;

    /**
     * @param VerticalRepositoryInterface $verticalRepo
     * @param ProviderRepositoryInterface $providerRepo
     * @param ShowroomRepositoryInterface $showroomRepo
     */
    public function __construct(
        VerticalRepositoryInterface $verticalRepo,
        ProviderRepositoryInterface $providerRepo,
        ShowroomRepositoryInterface $showroomRepo
    ) {
        $this->verticalRepo = $verticalRepo;
        $this->providerRepo = $providerRepo;
        $this->showroomRepo = $showroomRepo;
    }

    /**
     * @param $domain
     * @param $providerId
     * @param $score
     * @return mixed
     */
    public function createShowroom($domain, $providerId, $score)
    {
        $vertical = $this->verticalRepo->findOneByDomain($domain);
        $provider = $this->providerRepo->findOneById($providerId);

        if ($existingShowroom = $this->verticalRepo->findShowroom($vertical, $provider)) {
            return $existingShowroom;
        }

        $showroom = $vertical->addShowroom($provider, $score);
        $this->showroomRepo->save($showroom);

        return $showroom;
    }
}
