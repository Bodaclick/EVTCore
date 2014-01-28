<?php


namespace EVT\CoreDomainBundle\Form\Type;


use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GenericUserFormType extends RegistrationFormType
{
    public function __construct()
    {
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'EVT\CoreDomainBundle\Entity\GenericUser',
                'intention'  => 'registration',
                'csrf_protection' => false
            ));
    }

    public function getName()
    {
        return 'user';
    }

} 