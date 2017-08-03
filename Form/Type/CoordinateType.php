<?php

namespace KRG\AddressBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ivory\GoogleMap\Base\Coordinate;

class CoordinateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('latitude', 'hidden')
            ->add('longitude', 'hidden');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'   => Coordinate::class,
            'label_format' => 'form.tag.%name%',
        ));
    }
}
