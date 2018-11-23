<?php

namespace KRG\AddressBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use KRG\AddressBundle\Model\CoordinatesModel;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class CoordinateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('latitude', HiddenType::class)
            ->add('longitude', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'   => CoordinatesModel::class,
            'label_format' => 'form.address.%name%',
        ]);
    }
}
