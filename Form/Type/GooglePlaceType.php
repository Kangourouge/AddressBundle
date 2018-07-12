<?php

namespace KRG\AddressBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Ivory\GoogleMap\Helper\ApiHelper;

class GooglePlaceType extends TextType
{
    /**
     * @var ApiHelper
     */
    private $apiHelper;

    /**
     * @var string
     */
    private $apiKey;

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
        parent::buildView($view, $form, $options);

        $view->vars['options'] = array(
            'types' => $options['types'],
            'componentRestrictions' => $options['component_restrictions']
        );
        $view->vars['address_type'] = $options['address_type'];
        $view->vars['address_format'] = $options['address_format'];
        $view->vars['api'] = !$this->apiHelper->isLoaded() ? $this->apiHelper->render($this->locale, array('places')) : null;
        $this->apiHelper->isLoaded(true);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(array('component_restrictions', 'types'));
        $resolver->setDefaults(array(
            'location'       => null,
            'address_type'   => null,
            'address_format' => 'long_name',
        ));
        $resolver->setAllowedTypes(array(
            'component_restrictions' => 'array',
            'types'                  => 'array',
            'location'               => array('null', \Geocoder\Model\Coordinates::class),
            'address_type'           => array('null', 'string'),
            'address_format'         => 'string',
        ));
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
