<?php

namespace KRG\AddressBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use KRG\AddressBundle\Data\FranceData;
use KRG\AddressBundle\Entity\AddressInterface;
use KRG\AddressBundle\Entity\CountryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
        $this->country = $country;
        $this->addressClass = $entityManager->getClassMetadata(AddressInterface::class)->getName();
        $this->countryClass = $entityManager->getClassMetadata(CountryInterface::class)->getName();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', EntityType::class, array(
                'class'         => $this->countryClass,
                'label'         => false,
                'attr'          => array('placeholder' => 'form.address.country'),
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
                'component_restrictions' => array('country' => $this->country),
                'types'                  => array(),
                'label'                  => false,
                'attr'                   => array('placeholder' => 'form.address.address1'),
            ))
            ->add('address2', TextType::class, array(
                'required' => false,
                'label'    => false,
                'attr'     => array('placeholder' => 'form.address.address2'),
            ))
            ->add('city', GooglePlaceType::class, array(
                'label'                  => false,
                'component_restrictions' => array('country' => $this->country),
                'types'                  => array('(regions)'),
                'attr'                   => array('placeholder' => 'form.address.city'),
            ))
            ->add('postalCode', TextType::class, array(
                'label'                  => false,
                'attr'                   => array('placeholder' => 'form.address.postalCode'),
            ))
            ->add('latitude', HiddenType::class)
            ->add('longitude', HiddenType::class)
            ->add('department', HiddenType::class)
            ->add('region', HiddenType::class)
            ->add('approximate', HiddenType::class, array(
                'empty_data' => true
            ));

        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        parent::finishView($view, $form, $options);

        $view->children['address1']->vars['attr']['data-country'] = $view->children['country']->vars['id'];
        $view->children['postalCode']->vars['attr']['data-country'] = $view->children['country']->vars['id'];
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->addressClass,
        ));
    }

    public function getName()
    {
        return 'address';
    }
}
