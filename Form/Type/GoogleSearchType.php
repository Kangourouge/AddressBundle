<?php

namespace KRG\AddressBundle\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use KRG\AddressBundle\Entity\CountryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use KRG\AddressBundle\Form\DataTransformer\GooglePlaceTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoogleSearchType extends AbstractType
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $countries = $this->entityManager->getRepository(CountryInterface::class)->findBy(['active' => 1]);
        $countryCodes = array_map(function(CountryInterface $country) { return $country->getCode(); }, $countries);

        $builder
            ->add('location', GooglePlaceType::class, [
                'required'               => $options['required'],
                'data'                   => $options['data'],
                'types'                  => ['geocode'],
                'component_restrictions' => [
                    'country' => $countryCodes,
                ],
            ])
            ->add('place', HiddenType::class);

        $builder->addModelTransformer(new GooglePlaceTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required'       => false,
            'data'           => null,
            'label_format'   => 'form.address.%name%',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'google_search';
    }
}
