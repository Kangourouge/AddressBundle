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
            ->add('location', GooglePlaceType::class, array(
                'required'               => isset($options['required']) ? $options['required'] : false,
                'component_restrictions' => $options['component_restrictions'],
                'types'                  => $options['types'],
                'address_type'           => $options['address_type'],
                'address_format'         => $options['address_format'],
                'data'                   => isset($options['data']) ? $options['data'] : null,
            ))
            ->add('place', HiddenType::class);

        $builder->addModelTransformer(new GooglePlaceTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(array('component_restrictions'));
        $resolver->setDefaults(array(
            'component_restrictions' => array('country' => array('fr', 'be')),
            'location'               => null,
            'address_type'           => null,
            'address_format'         => 'long_name',
            'types'                  => array('geocode'),
        ));
        $resolver->setAllowedTypes(array(
            'component_restrictions' => 'array',
            'types'                  => 'array',
            'location'               => array('null', \Geocoder\Model\Coordinates::class),
            'address_type'           => array('null', 'string'),
            'address_format'         => 'string',
        ));
    }
}
