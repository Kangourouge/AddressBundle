<?php

namespace KRG\AddressBundle\Model;

use KRG\AddressBundle\Entity\AddressInterface;
use Ivory\GoogleMap\Base\Coordinate;

class GooglePlaceModel
{
	/**
	 * @var array
	 */
	private $data;

	/**
	 * @var array
	 */
	private $normalizedData;

	/**
	 * @var AddressInterface
	 */
	private $address;

	function __construct(array $data)
	{
		$this->data = $data;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	public function getFormattedAddress()
	{
		return $this->data['formatted_address'];
	}

	public function getType()
	{
		$this->data['types'][0];

		switch ($this->data['types'][0]){
			case 'street_address':
			case 'route':
            case 'postal_code':
            case 'neighborhood':
				return 'coordinate';
			case 'locality':
				return 'city';
			case 'administrative_area_level_2':
				return 'department';
			case 'administrative_area_level_1':
				return 'region';
		}
		return null;
	}

	public function getCoordinate()
	{
	    $latitude = $this->data['coordinate']['latitude'] ?? $this->data['geometry']['location']['lat'];
        $longitude = $this->data['coordinate']['longitude'] ?? $this->data['geometry']['location']['lng'];

		$coordinate = new Coordinate();
		$coordinate->setLatitude($latitude);
		$coordinate->setLongitude($longitude);

		return $coordinate;
	}

	private function getProperty($property, $name)
	{
		if (!isset($this->normalizedData[$property])) {
			$this->normalizedData[$property] = array();
		} else if (isset($this->normalizedData[$property][$name])) {
			return $this->normalizedData[$property][$name];
		}

		foreach ($this->data['address_components'] as $component) {
			if ($component['types'][0] == $property) {
				return $this->normalizedData[$property][$name] = $component[$name];
			}
		}

		return $this->normalizedData[$property][$name] = null;
	}

	public function getAddress()
	{
		if ($this->address instanceof AddressInterface) {
			return $this->address;
		}

		$this->address = new AddressModel();
		$this->address->setCity($this->getProperty('locality', 'long_name'));
		$this->address->setRegion($this->getProperty('administrative_area_level_1', 'long_name'));
		$this->address->setDepartment($this->getProperty('administrative_area_level_2', 'long_name'));
		$this->address->setPostalCode($this->getProperty('postal_code', 'long_name'));

		$this->address->setAddress1($this->getProperty('route', 'long_name'));
        if ($this->getProperty('street_number', 'long_name') !== null) {
            $this->address->setAddress1($this->getProperty('street_number', 'long_name').' '.$this->getProperty('route', 'long_name'));
        }

		return $this->address;
	}

	public function getVicinity()
    {
        if (key_exists('vicinity', $this->data)) {
            return $this->data['vicinity'];
        }

        return '';
    }
}
