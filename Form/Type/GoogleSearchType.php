<?php

namespace KRG\AddressBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use KRG\AddressBundle\Form\DataTransformer\GooglePlaceTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoogleSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('location', GooglePlaceType::class, $options)
            ->add('place', HiddenType::class);

        $builder->addModelTransformer(new GooglePlaceTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'component_restrictions' => array('country' => array('fr', 'be')),
            'types'                  => array('geocode'),
            'address_type'           => 'locality',
            'address_format'         => 'short_name',
        ));
        $resolver->setAllowedTypes(array(
            'component_restrictions' => 'array',
            'types'                  => 'array',
            'address_type'           => array('null', 'string'),
            'address_format'         => 'string',
        ));
    }
}
