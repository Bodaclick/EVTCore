<?php

namespace EVT\ApiBundle\Factory;

use EVT\CoreDomain\Email;
use EVT\CoreDomain\EmailCollection;
use EVT\CoreDomain\Lead\Location;
use EVT\CoreDomain\Provider\ProviderId;
use EVT\CoreDomain\Provider\Provider;
use EVT\CoreDomain\Provider\ProviderRepositoryInterface;
use EVT\CoreDomain\RepositoryInterface;

/**
 * Providerfactory
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class ProviderFactory
{
    protected $providerRepo;
    protected $userRepo;

    public function __construct(ProviderRepositoryInterface $providerRepo, RepositoryInterface $userRepo)
    {
        $this->providerRepo = $providerRepo;
        $this->userRepo = $userRepo;
    }

    /**
     *
     * @param array $providerRequest
     * @return \EVT\CoreDomain\Provider\Provider
     */
    public function createProvider($providerRequest)
    {
        $notificationEmails = $providerRequest['notificationEmails'];
        if (!is_array($notificationEmails)) {
            throw new \InvalidArgumentException('notificationEmails must be an array');
        }

        $emails = new EmailCollection(new Email(array_shift($notificationEmails)));
        foreach ($notificationEmails as $email) {
            $emails->append(new Email($email));
        }

        $location = new Location(
            $providerRequest['locationLat'],
            $providerRequest['locationLong'],
            $providerRequest['locationAdminLevel1'],
            $providerRequest['locationAdminLevel2'],
            $providerRequest['locationCountry']
        );

        $provider = new Provider(new ProviderId(''), $providerRequest['name'], $emails, $location);
        $provider->setPhone($providerRequest['phone']);

        $manager = $this->userRepo->getManagerById($providerRequest['genericUser']);
        if (null === $manager) {
            throw new \InvalidArgumentException('Manager not found');
        }
        $provider->addManager($manager);

        if ($previousProvider = $this->providerRepo->findExistingProvider($provider)) {
            return $previousProvider;
        }

        $this->providerRepo->save($provider);
        return $provider;
    }
}
