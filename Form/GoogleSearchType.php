<?php

namespace KRG\AddressBundle\Form;

use Geocoder\Model\Coordinates;
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
            ->add('location', GooglePlaceType::class, [
                'required'               => isset($options['required']) ? $options['required'] : false,
                'component_restrictions' => $options['component_restrictions'],
                'types'                  => $options['types'],
                'address_type'           => $options['address_type'],
                'address_format'         => $options['address_format'],
                'data'                   => isset($options['data']) ? $options['data'] : null,
            ])
            ->add('place', HiddenType::class);

        $builder->addModelTransformer(new GooglePlaceTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component_restrictions' => ['country' => ['fr']],
            'location'               => null,
            'address_type'           => null,
            'address_format'         => 'long_name',
            'types'                  => ['geocode'],
            'label_format'           => 'form.address.%name%',
        ]);
        $resolver->setRequired(['component_restrictions']);
        $resolver
            ->setAllowedTypes('component_restrictions', 'array')
            ->setAllowedTypes('types', 'array')
            ->setAllowedTypes('location', ['null', Coordinates::class])
            ->setAllowedTypes('address_type', ['null', 'string'])
            ->setAllowedTypes('address_format', 'string');
    }
}
