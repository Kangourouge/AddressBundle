<?php

namespace KRG\AddressBundle\Form\DataTransformer;

use KRG\AddressBundle\Model\GooglePlaceModel;
use Symfony\Component\Form\DataTransformerInterface;

class GooglePlaceTransformer implements DataTransformerInterface
{
	public function transform($value)
	{
        if (!$value instanceof GooglePlaceModel){
			return array(
                'location' => null,
                'place'    => null,
			);
		}

		return array(
			'location' => $value->getFormattedAddress(),
            'place'    => json_encode($value->getData())
		);

	}

	public function reverseTransform($value)
	{
		if ($value === null || !isset($value['place']) || $value['place'] === null){
			return null;
		}

		return new GooglePlaceModel(json_decode($value['place'], true));
	}
}
