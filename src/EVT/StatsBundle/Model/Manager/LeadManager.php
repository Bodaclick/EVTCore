<?php
namespace EVT\StatsBundle\Model\Manager;

use Doctrine\ORM\EntityManager;
use EVT\CoreDomainBundle\Repository\ProviderRepository;
use EVT\CoreDomainBundle\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LeadManager
{
    private $providerRepo;
    private $userRepo;
    private $em;

    /**
     * @param EntityManager   $em
     */
    public function __construct(ProviderRepository $pr, UserRepository $ur, EntityManager $em)
    {
        $this->providerRepo = $pr;
        $this->userRepo = $ur;
        $this->em = $em;
    }

    public function getLeadsBetweenDates ($username, $from_date, $to_date)
    {
        $providers = $this->providerRepo->findByUser($username);

        if ($providers != null){
            foreach ($providers as $key=>$provider){
                $pResult = $this->em->getRepository('EVTStatsBundle:Lead')
                    ->findByProviderBetweenDates($provider->getId(), $from_date, $to_date);
                if (!empty($pResult)){
                    if ($key == 0){
                        $result = $pResult;
                    }else{
                        $result [] = $pResult;
                    }
                }else{
                    $result = null;
                }
            }
        }else{
            if (null != $this->userRepo->getEmployeeByUsername($username)){
                $result = $this->em->getRepository('EVTStatsBundle:Lead')->findBetweenDates($from_date, $to_date);
            }else{
                $result = null;
            }
        }

        return $result;
    }

} 