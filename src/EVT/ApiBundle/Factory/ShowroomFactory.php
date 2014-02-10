<?php

namespace EVT\ApiBundle\Factory;

use EVT\CoreDomain\Provider\ProviderRepositoryInterface;
use EVT\CoreDomain\Provider\VerticalRepositoryInterface;
use EVT\CoreDomain\Provider\ShowroomRepositoryInterface;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use JMS\Serializer\Serializer;

/**
 * Class ShowroomFactory
 * @copyright 2014 Bodaclick
 */
class ShowroomFactory
{
    protected $verticalRepo;
    protected $providerRepo;
    protected $showroomRepo;
    protected $emdQueue;
    protected $serializer;

    /**
     *
     * @param VerticalRepositoryInterface $verticalRepo
     * @param ProviderRepositoryInterface $providerRepo
     * @param ShowroomRepositoryInterface $showroomRepo
     * @param Producer $emdQueue
     * @param Serializer $serializer
     */
    public function __construct(
        VerticalRepositoryInterface $verticalRepo,
        ProviderRepositoryInterface $providerRepo,
        ShowroomRepositoryInterface $showroomRepo,
        Producer $emdQueue,
        Serializer $serializer
    ) {
        $this->verticalRepo = $verticalRepo;
        $this->providerRepo = $providerRepo;
        $this->showroomRepo = $showroomRepo;
        $this->emdQueue = $emdQueue;
        $this->serializer = $serializer;
    }

    /**
     * @param $domain
     * @param $providerId
     * @param $score
     * @throws \InvalidArgumentException
     * @return mixed
     */
    public function createShowroom(array $data, $extra_data = '')
    {
        $vertical = $this->verticalRepo->findOneByDomain($data['vertical']);
        if (null === $vertical) {
            throw new \InvalidArgumentException('Vertical not found');
        }
        $provider = $this->providerRepo->findOneById($data['provider']);
        if (null === $provider) {
            throw new \InvalidArgumentException('Provider not found');
        }

        if ($existingShowroom = $this->verticalRepo->findShowroom($vertical, $provider)) {
            return $existingShowroom;
        }

        $showroom = $vertical->addShowroom($provider, $data['score']);
        $this->showroomRepo->save($showroom);
        $this->sendToEMD($showroom, $extra_data);

        return $showroom;
    }

    private function sendToEMD($showroom, $extra_data)
    {
        $msg = new ShowroomWithExtraData($showroom, $extra_data);

        $this->emdQueue->publish($this->serializer->serialize($msg, 'json'));
    }
}
