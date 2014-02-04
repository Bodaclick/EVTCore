<?php

namespace EVT\ApiBundle\Factory;

use EVT\CoreDomainBundle\Repository\ProviderRepository;
use EVT\CoreDomain\Email;
use EVT\CoreDomain\EmailCollection;
use EVT\CoreDomain\Provider\ProviderId;
use EVT\CoreDomain\Provider\Provider;
use EVT\CoreDomain\Provider\ProviderRepositoryInterface;
use EVT\CoreDomain\RepositoryInterface;
use FOS\UserBundle\EventListener\EmailConfirmationListener;

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

        $provider = new Provider(
            new ProviderId(''),
            $providerRequest['name'],
            $emails
        );

        $manager = $this->userRepo->getManagerById($providerRequest['genericUser']);
        if (null === $manager) {
            throw new \InvalidArgumentException('Manager not found');
        }
        $provider->addManager($manager);

        $this->providerRepo->save($provider);

        return $provider;
    }
}
