<?php

namespace KRG\AddressBundle\Form\Type;

use Doctrine\ORM\EntityManager;
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
    private $country;

    /**
     * @var string
     */
    private $addressClass;

    /**
     * @var string
     */
    private $countryClass;

    public function __construct(EntityManager $entityManager, $country)
    {
        $this->addressClass = $entityManager->getClassMetadata(AddressInterface::class)->getName();
        $this->countryClass = $entityManager->getClassMetadata(CountryInterface::class)->getName();
        $this->country = $country;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', EntityType::class, [
                'class'         => $this->countryClass,
                'label'         => false,
                'attr'          => [
                    'placeholder' => 'form.address.country'
                ],
                'choice_attr'   => function (CountryInterface $country, $key, $index) {
                    return ['data-code' => strtolower($country->getCode())];
                },
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('c')->orderBy('c.name');
                }
            ])
            ->add('name', TextType::class, [
                'label'    => false,
                'required' => false,
                'attr'     => [
                    'placeholder' => 'form.address.name'
                ],
            ])
            ->add('address1', GooglePlaceType::class, [
                'component_restrictions' => ['country' => $this->country],
                'types'                  => [],
                'label'                  => false,
                'attr'                   => [
                    'placeholder' => 'form.address.address1',
                ],
            ])
            ->add('address2', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr'     => [
                    'placeholder' => 'form.address.address2'
                ],
            ])
            ->add('postalCode', GooglePlaceType::class, [
                'component_restrictions' => ['country' => $this->country],
                'types'                  => ['(regions)'],
                'label'                  => false,
                'attr'                   => [
                    'placeholder' => 'form.address.postalCode'
                ],
            ])
            ->add('city', TextType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'form.address.city'
                ],
            ])
            ->add('latitude', HiddenType::class)
            ->add('longitude', HiddenType::class)
            ->add('department', HiddenType::class)
            ->add('region', HiddenType::class)
            ->add('approximate', HiddenType::class, [
                'empty_data' => true
            ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        parent::finishView($view, $form, $options);

        $view->children['address1']->vars['attr']['data-country'] = $view->children['country']->vars['id'];
        $view->children['postalCode']->vars['attr']['data-country'] = $view->children['country']->vars['id'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->addressClass,
        ]);
    }

    public function getName()
    {
        return 'address';
    }
}
