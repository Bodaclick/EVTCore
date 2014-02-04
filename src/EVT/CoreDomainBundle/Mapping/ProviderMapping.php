<?php

namespace EVT\CoreDomainBundle\Mapping;

use Doctrine\ORM\EntityManager;
use EVT\CoreDomain\Email;
use EVT\CoreDomain\EmailCollection;
use EVT\CoreDomain\Provider\Provider;
use EVT\CoreDomain\Provider\ProviderId;
use EVT\CoreDomain\User\Manager;
use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomainBundle\Entity\Provider as EntityProvider;
use EVT\CoreDomainBundle\Mapping\UserMapping;

/**
 * ProviderMapping
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @author    Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class ProviderMapping implements MappingInterface
{
    private $em;
    private $userMapper;

    public function __construct(EntityManager $em, UserMapping $userMapper)
    {
        $this->em = $em;
        $this->userMapper = $userMapper;
    }

    public function mapDomainToEntity($provider)
    {
        $eProvider = new EntityProvider();

        if (null !== $provider->getId()) {
            $rflProvider = new \ReflectionClass($eProvider);
            $rflId = $rflProvider->getProperty('id');
            $rflId->setAccessible(true);
            $rflId->setValue($eProvider, $provider->getId());
        }

        $eProvider->setName($provider->getName());
        $eProvider->setSlug($provider->getSlug());
        $eProvider->setPhone($provider->getPhone());
        $location = $provider->getLocation();

        if ($location) {
            $eProvider->setLocationAdminLevel1($location->getAdminLevel1());
            $eProvider->setLocationAdminLevel2($location->getAdminLevel2());
            $eProvider->setLocationCountry($location->getCountry());
        }
        $emails = $provider->getNotificationEmails();

        $eEmails = [];
        foreach ($emails as $email) {
            $eEmails[] = $email->getEmail();
        }

        $eProvider->setNotificationEmails($eEmails);

        $managers = $provider->getManagers()->getIterator();

        foreach ($managers as $manager) {
            $managerProxy = $this->em->getReference('EVT\CoreDomainBundle\Entity\GenericUser', $manager->getId());
            $eProvider->addGenericUser($managerProxy);
        }


        return $eProvider;
    }

    public function mapEntityToDomain($eProvider)
    {
        $pId = new ProviderId($eProvider->getId());

        $emails = $eProvider->getNotificationEmails();
        $notifEmails = new EmailCollection(new Email(array_shift($emails)));

        foreach ($emails as $email) {
            $notifEmails->append(new Email($email));
        }

        $dProvider = new Provider($pId, $eProvider->getName(), $notifEmails);
        $dProvider->setPhone($eProvider->getPhone());

        $managers = $eProvider->getGenericUser();
        foreach ($managers as $manager) {
            $dProvider->addManager($this->userMapper->mapEntityToDomain($manager));
        }

        return $dProvider;
    }
}
