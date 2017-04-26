<?php

namespace KRG\AddressBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use KRG\AddressBundle\Form\DataTransformer\GooglePlaceTransformer;

class GoogleSearchType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder
			->add('location', GooglePlaceType::class, array(
				'mapped' => false,
	            'required' => false,
	            'component_restrictions' => array('country' => array('fr','be')),
	            'types'                  => array('geocode'),
	            'address_type'           => 'locality',
	            'address_format'         => 'short_name',
			))
			->add('place', HiddenType::class);

		$builder->addModelTransformer(new GooglePlaceTransformer());
	}


}
