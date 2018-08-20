<?php

namespace KRG\AddressBundle\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use KRG\AddressBundle\Data\FranceData;
use KRG\AddressBundle\Entity\AddressInterface;
use KRG\AddressBundle\Entity\CountryInterface;
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
                'class'             => $this->entityManager->getClassMetadata(CountryInterface::class)->getName(),
                'attr'              => ['placeholder' => 'form.address.country'],
                'choice_attr'       => function (CountryInterface $country, $key, $index) {
                    if (strlen($country->getCode()) === 0) {
                        throw new InvalidConfigurationException('Each countries must have a country code in database.');
                    }

                    return ['data-code' => strtolower($country->getCode())];
                },
                'query_builder'     => function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('country')->where('country.active = 1')->orderBy('country.name');
                },
                'preferred_choices' => function ($value, $key) {
                    return $value->isPrefered();
                },
            ])
            ->add('name', TextType::class, [
                'required' => false,
                'attr'     => ['placeholder' => 'form.address.name'],
            ])
            ->add('address1', GooglePlaceType::class, [
                'attr'                   => ['placeholder' => 'form.address.address1',],
                'component_restrictions' => ['country' => $this->countries],
                'types'                  => [],
            ])
            ->add('address2', TextType::class, [
                'required' => false,
                'attr'     => ['placeholder' => 'form.address.address2'],
            ])
            ->add('postalCode', TextType::class, [
                'attr' => ['placeholder' => 'form.address.postalCode'],
            ])
            ->add('city', GooglePlaceType::class, [
                'attr'                   => [
                    'placeholder' => 'form.address.city'
                ],
                'component_restrictions' => ['country' => $this->countries],
                'types'                  => ['(regions)'],
            ])
            ->add('latitude', HiddenType::class)
            ->add('longitude', HiddenType::class)
            ->add('department', HiddenType::class)
            ->add('region', HiddenType::class);

        $builder->addEventListener(FormEvents::POST_SET_DATA, [$this, 'onPostSetData']);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
    }

    public function onPostSetData(FormEvent $event)
    {
        $form = $event->getForm();
        /** @var $data AddressInterface */
        $data = $event->getData();

        $form->add('approximate', HiddenType::class, [
            'data' => (int)($data === null || $data->isApproximate())
        ]);
    }

    public function onPreSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // Handle missing Google Autocomplete attributes
        if ('' === $data['department']) {
            $data['department'] = FranceData::getDepartmentByPostalCode(substr($data['postalCode'], 0, 2));
            $event->setData($data);
        }
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['ask_precise_coordinates'] = $options['ask_precise_coordinates'];
        $view->children['address1']->vars['attr']['data-country'] = $view->children['country']->vars['id'];
        $view->children['postalCode']->vars['attr']['data-country'] = $view->children['country']->vars['id'];

        if (null === $options['label_format']) {
            foreach ($view->children as $child) {
                $child->vars['label'] = false;
            }
        }
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
