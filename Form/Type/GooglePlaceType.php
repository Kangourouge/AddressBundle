<?php

namespace KRG\AddressBundle\Form\Type;

use KRG\AddressBundle\Helper\ApiHelperInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GooglePlaceType extends AbstractType
{
    /** @var ApiHelperInterface */
    protected $apiHelper;

    function __construct(ApiHelperInterface $apiHelper)
    {
        $this->apiHelper = $apiHelper;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['options'] = [
            'types'                 => $options['types'],
            'componentRestrictions' => $options['component_restrictions'],
        ];
        $view->vars['api'] = $this->apiHelper->render();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label_format'           => 'form.address.%name%',
            'types'                  => [],
            'component_restrictions' => [],
        ]);

        $resolver
            ->setAllowedTypes('component_restrictions', 'array')
            ->setAllowedTypes('types', 'array');
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function getBlockPrefix()
    {
        return 'google_place';
    }
}
