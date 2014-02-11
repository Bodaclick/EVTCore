<?php


namespace EVT\CoreDomainBundle\Form\Type;


use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class GenericUserFormType
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class GenericUserFormType extends RegistrationFormType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('name', 'text')
            ->add('surnames', 'text')
            ->add('phone', 'text');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'EVT\CoreDomainBundle\Entity\GenericUser',
                'intention'  => 'registration',
                'csrf_protection' => false,
                'validation_groups' => ['Registration'],
                'error_bubling' => false
            )
        );
    }

    public function getName()
    {
        return 'user';
    }
}
