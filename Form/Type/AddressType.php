<?php

namespace KRG\AddressBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use KRG\AddressBundle\Entity\AddressInterface;
use KRG\AddressBundle\Entity\CountryInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Exception\InvalidConfigurationException;

class AddressType extends AbstractType
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var string */
    protected $countries;

    public function __construct(EntityManagerInterface $entityManager, $countries)
    {
        $this->entityManager = $entityManager;
        $this->countries = $countries;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', EntityType::class, [
                'class'         => $this->entityManager->getClassMetadata(CountryInterface::class)->getName(),
                'label'         => false,
                'attr'          => ['placeholder' => 'form.address.country'],
                'choice_attr'   => function (CountryInterface $country, $key, $index) {
                    if (strlen($country->getCode()) === 0) {
                        throw new InvalidConfigurationException('Each countries must have a country code in database.');
                    }

                    return ['data-code' => strtolower($country->getCode())];
                },
                'query_builder' => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('c')->orderBy('c.name');
                },
            ])
            ->add('name', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr'     => ['placeholder' => 'form.address.name'],
            ])
            ->add('address1', GooglePlaceType::class, [
                'label'                  => false,
                'attr'                   => ['placeholder' => 'form.address.address1',],
                'component_restrictions' => ['country' => $this->countries],
                'types'                  => [],
            ])
            ->add('address2', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr'     => ['placeholder' => 'form.address.address2'],
            ])
            ->add('postalCode', GooglePlaceType::class, [
                'label'                  => false,
                'attr'                   => ['placeholder' => 'form.address.postalCode'],
                'component_restrictions' => ['country' => $this->countries],
                'types'                  => ['(regions)'],
            ])
            ->add('city', TextType::class, [
                'attr'  => [
                    'placeholder' => 'form.address.city'
                ],
                'label' => false,
            ])
            ->add('latitude', HiddenType::class)
            ->add('longitude', HiddenType::class)
            ->add('department', HiddenType::class)
            ->add('region', HiddenType::class);

        $builder->addEventListener(FormEvents::POST_SET_DATA, [$this, 'onPostSetData']);
    }

    public function onPostSetData(FormEvent $event)
    {
        $form = $event->getForm();
        /** @var $data AddressInterface */
        $data = $event->getData();

        $form->add('approximate', HiddenType::class, [
            'data' => ($data === null || $data->isApproximate())
        ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        parent::finishView($view, $form, $options);

        $view->vars['ask_precise_coordinates'] = $options['ask_precise_coordinates'];
        $view->children['address1']->vars['attr']['data-country'] = $view->children['country']->vars['id'];
        $view->children['postalCode']->vars['attr']['data-country'] = $view->children['country']->vars['id'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'              => $this->entityManager->getClassMetadata(AddressInterface::class)->getName(),
            'label_format'            => 'form.address.%name%',
            'ask_precise_coordinates' => true
        ]);
    }

    public function getBlockPrefix()
    {
        return 'address';
    }
}
