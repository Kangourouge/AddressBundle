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
    /**
     * @var string
     */
    private $addressClass;

    /**
     * @var string
     */
    private $countryClass;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->addressClass = $entityManager->getClassMetadata(AddressInterface::class)->getName();
        $this->countryClass = $entityManager->getClassMetadata(CountryInterface::class)->getName();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', EntityType::class, array(
                'class'         => $this->countryClass,
                'label'         => false,
                'attr'          => array('placeholder' => 'form.address.country', 'data-sonata-select2' => 'false'),
                'choice_attr'   => function (CountryInterface $country, $key, $index) {
                    return array('data-code' => strtolower($country->getCode()));
                },
                'query_builder' => function (EntityRepository $repository) {
                    return $repository
                        ->createQueryBuilder('c')
                        ->orderBy('c.name');
                }
            ))
            ->add('name', TextType::class, array(
                'label'    => false,
                'required' => false,
                'attr'     => array('placeholder' => 'form.address.name'),
            ))
            ->add('address1', GooglePlaceType::class, array(
                'label' => false,
                'attr'  => array('placeholder' => 'form.address.address1'),
            ))
            ->add('address2', TextType::class, array(
                'required' => false,
                'label'    => false,
                'attr'     => array('placeholder' => 'form.address.address2'),
            ))
            ->add('postalCode', GooglePlaceType::class, array(
                'types' => array('(regions)'),
                'label' => false,
                'attr'  => array('placeholder' => 'form.address.postalCode'),
            ))
            ->add('city', TextType::class, array(
                'label' => false,
                'attr'  => array('placeholder' => 'form.address.city'),
            ))
            ->add('latitude', HiddenType::class)
            ->add('longitude', HiddenType::class)
            ->add('department', HiddenType::class)
            ->add('region', HiddenType::class)
            ->add('approximate', HiddenType::class, array(
                'empty_data' => true
            ));
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        parent::finishView($view, $form, $options);

        $view->children['address1']->vars['attr']['data-country'] = $view->children['country']->vars['id'];
        $view->children['postalCode']->vars['attr']['data-country'] = $view->children['country']->vars['id'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->addressClass,
        ));
    }
}
