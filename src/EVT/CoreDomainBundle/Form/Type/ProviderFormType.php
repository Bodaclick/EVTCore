<?php

namespace EVT\CoreDomainBundle\Form\Type;

use EVT\CoreDomainBundle\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

/**
 * ProviderFormType
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class ProviderFormType extends AbstractType
{
    private $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'genericUser',
                'entity',
                [
                    'class' => 'EVTCoreDomainBundle:GenericUser',
                    'query_builder' => function (EntityRepository $er) {
                        return $this->userRepo->getRetriveManagersQueryBuilder();
                    },
                    'expanded'  => false,
                    'multiple'  => true,
                    'property' => 'id',
                    'error_bubbling' => true
              ]
            )
            ->add('name', 'text', ['error_bubbling' => true])
            ->add('phone', 'text', ['error_bubbling' => true])
            ->add('slug', 'text', ['error_bubbling' => true])
            ->add('locationAdminLevel1', 'text', ['error_bubbling' => true])
            ->add('locationAdminLevel2', 'text', ['error_bubbling' => true])
            ->add('locationCountry', 'text', ['error_bubbling' => true])
            ->add('locationLat', 'integer', ['error_bubbling' => true])
            ->add('locationLong', 'integer', ['error_bubbling' => true])
            ->add('notificationEmails', 'text', ['error_bubbling' => true]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'EVT\CoreDomainBundle\Entity\Provider',
                'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'provider';
    }
}
