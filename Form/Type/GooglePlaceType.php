<?php

namespace KRG\AddressBundle\Form\Type;

use KRG\AddressBundle\Helper\ApiHelper;
use KRG\AddressBundle\Model\Coordinates;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class GooglePlaceType extends TextType
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var ApiHelper
     */
    private $apiHelper;

    /**
     * @var string
     */
    private $locale;

    function __construct(ApiHelper $apiHelper, $apiKey, $locale)
    {
        $this->apiHelper = $apiHelper;
        $this->apiKey = $apiKey;
        $this->locale = $locale;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['options'] = [
            'types'                 => $options['types'],
            'componentRestrictions' => $options['component_restrictions'],
        ];
        $view->vars['address_type'] = $options['address_type'];
        $view->vars['address_format'] = $options['address_format'];
        $view->vars['api'] = $this->apiHelper->render();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'location'       => null,
            'address_type'   => null,
            'address_format' => 'long_name',
            'label_format'   => 'form.address.%name%',
        ]);
        $resolver->setRequired(['component_restrictions', 'types']);
        $resolver
            ->setAllowedTypes('component_restrictions', 'array')
            ->setAllowedTypes('types', 'array')
            ->setAllowedTypes('location', ['null', Coordinates::class])
            ->setAllowedTypes('address_type', ['null', 'string'])
            ->setAllowedTypes('address_format', 'string');
    }

    public function getBlockPrefix()
    {
        return $this->getName();
    }

    public function getName()
    {
        return 'google_place';
    }
}
