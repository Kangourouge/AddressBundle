<?php

namespace KRG\AddressBundle\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use KRG\AddressBundle\Data\FranceData;
use KRG\AddressBundle\Entity\AddressInterface;
use KRG\AddressBundle\Entity\CountryInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Exception\InvalidConfigurationException;

class AddressType extends AbstractType
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $countries = $this->entityManager->getRepository(CountryInterface::class)->findBy(['active' => 1]);

        $builder
            ->add('country', ChoiceType::class, [
                'choices'           => $countries,
                'choice_attr'       => function (CountryInterface $country) {
                    if (0 === strlen($country->getCode())) {
                        throw new InvalidConfigurationException('Each countries must have a country code in database.');
                    }

                    return ['data-code' => $country->getCode()];
                },
                'choice_label'      => function (CountryInterface $country) {
                    return $country->getName();
                },
                'preferred_choices' => function (CountryInterface $country) {
                    return $country->isPrefered();
                },
            ])
            ->add('name')
            ->add('address1', GooglePlaceType::class)
            ->add('address2', TextType::class, [
                'required' => false,
            ])
            ->add('postalCode', TextType::class)
            ->add('city', GooglePlaceType::class, [
                'types' => ['(regions)'],
            ])
            ->add('latitude', HiddenType::class)
            ->add('longitude', HiddenType::class)
            ->add('department', HiddenType::class)
            ->add('region', HiddenType::class);

        $builder
            ->addEventListener(FormEvents::POST_SET_DATA, [$this, 'onPostSetData'])
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
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

        // Handle missing Google Autocomplete attributes
        if (0 === strlen($data['department'])) {
            $data['department'] = FranceData::getDepartmentByPostalCode(substr($data['postalCode'], 0, 2));
        }

        if (0 === strlen($data['address1']) && 0 === strlen($data['address2'])) {
            $data = null;
        }

        $event->setData($data);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->children['address1']->vars['attr']['data-country'] = $view->children['country']->vars['id'];
        $view->children['city']->vars['attr']['data-country'] = $view->children['country']->vars['id'];
        $view->vars['ask_precise_coordinates'] = $options['ask_precise_coordinates'];

        if (null === $options['label_format']) {
            foreach ($view->children as $child) {
                $child->vars['label'] = false;
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'              => $this->entityManager->getClassMetadata(AddressInterface::class)->getReflectionClass()->getName(),
            'required'                => false,
            'label_format'            => 'form.address.%name%',
            'validation_groups'       => ['Localize'],
            'ask_precise_coordinates' => true,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'address';
    }
}
