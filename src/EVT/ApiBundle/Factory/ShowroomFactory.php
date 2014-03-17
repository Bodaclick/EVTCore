<?php

namespace EVT\ApiBundle\Factory;

use EVT\CoreDomain\Provider\ShowroomType;
use EVT\CoreDomain\InformationBag;
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
     * @param array  $data
     * @param string $extra_data
     * @return Showroom
     * @throws \InvalidArgumentException
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

        $infoBag = $this->createInfoBag($data);

        $showroom = $vertical->addShowroom($provider, new ShowroomType($data['type']), $infoBag, $extra_data);
        $this->showroomRepo->save($showroom);
        $this->sendToEMD($showroom);

        return $showroom;
    }

    private function createInfoBag($data)
    {
        $infoBag = new InformationBag();
        if (isset($data['name'])) {
            $infoBag->set('name', $data['name']);
        }

        if (isset($data['phone'])) {
            $infoBag->set('phone', $data['phone']);
        }

        if (isset($data['slug'])) {
            $infoBag->set('slug', $data['slug']);
        }

        return $infoBag;
    }

    private function sendToEMD($showroom)
    {
        $this->emdQueue->publish($this->serializer->serialize($showroom, 'json'));
    }
}
