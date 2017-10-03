<?php

namespace KRG\AddressBundle\Form\Type;

use KRG\AddressBundle\Helper\ApiHelper;
use KRG\AddressBundle\Model\CoordinatesModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class GooglePlaceType extends AbstractType
{
    /**
     * @var ApiHelper
     */
    private $apiHelper;

    public function __construct(ApiHelper $apiHelper)
    {
        $this->apiHelper = $apiHelper;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['options'] = array(
            'types'                 => $options['types'],
        );

        $view->vars['address_type'] = $options['address_type'];
        $view->vars['address_format'] = $options['address_format'];
        $view->vars['api'] = $this->apiHelper->render();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired(array('types'))
            ->setDefaults(array(
                'location'       => null,
                'address_type'   => null,
                'address_format' => 'long_name',
                'types'          => array(),
            ))
            ->setAllowedTypes('types', 'array')
            ->setAllowedTypes('location', array('null', CoordinatesModel::class))
            ->setAllowedTypes('address_type', array('null', 'string'))
            ->setAllowedTypes('address_format', 'string');
    }

    public function getParent()
    {
        return TextType::class;
    }
}
