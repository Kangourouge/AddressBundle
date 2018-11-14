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
            'types' => $options['types'],
        );

        $view->vars['address_type'] = $options['address_type'];
        $view->vars['address_format'] = $options['address_format'];
        $view->vars['api'] = $this->apiHelper->render();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('location', null)
            ->setDefault('address_type', null)
            ->setDefault('address_format', 'long_name')
            ->setDefault('types', ['types'])
            ->setDefault('required', false)
            ->setAllowedTypes('types', 'array')
            ->setAllowedTypes('location', ['null', CoordinatesModel::class])
            ->setAllowedTypes('address_type', ['null', 'string'])
            ->setAllowedTypes('address_format', 'string');
    }

    public function getParent()
    {
        return TextType::class;
    }
}
