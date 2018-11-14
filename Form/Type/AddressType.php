<?php

namespace KRG\AddressBundle\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use KRG\AddressBundle\Entity\AddressInterface;
use KRG\AddressBundle\Entity\CountryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', null, array(
                'choice_attr'   => function (CountryInterface $country, $key, $index) {
                    return array('data-code' => strtolower($country->getCode()));
                }
            ))
            ->add('name')
            ->add('address1', GooglePlaceType::class)
            ->add('address2')
            ->add('postalCode', GooglePlaceType::class, ['types' => ['(regions)']])
            ->add('city')
            ->add('latitude', HiddenType::class)
            ->add('longitude', HiddenType::class)
            ->add('department', HiddenType::class)
            ->add('region', HiddenType::class)
            ->add('approximate', HiddenType::class, ['empty_data' => true]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        parent::finishView($view, $form, $options);

        $view->children['address1']->vars['attr']['data-country'] = $view->children['country']->vars['id'];
        $view->children['postalCode']->vars['attr']['data-country'] = $view->children['country']->vars['id'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', AddressInterface::class);
        $resolver->setDefault('required', false);
        $resolver->setDefault('label_format', 'form.address.%name%');
        $resolver->setDefault('validation_groups', ['Localize']);
    }
}
