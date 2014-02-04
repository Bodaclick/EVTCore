<?php

namespace EVT\ApiBundle\Factory;

use EVT\CoreDomain\Provider\ProviderRepositoryInterface;
use EVT\CoreDomain\Provider\VerticalRepositoryInterface;
use EVT\CoreDomain\Provider\ShowroomRepositoryInterface;

/**
 * ShowroomFactory
 *
 * @author    Quique Torras <etorras@gmail.com>
 * @copyright 2014 Bodaclick S.A
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
     * @return EVT\CoreDomain\Provider\Showroom
     */
    public function createShowroom($domain, $providerId, $score)
    {
        $vertical = $this->verticalRepo->findOneByDomain($domain);
        $provider = $this->providerRepo->find($providerId);

        $showroom = $vertical->addShowroom($provider, $score);

        $this->showroomRepo->save($showroom);

        return $showroom;
    }
}
