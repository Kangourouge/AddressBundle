<?php

namespace KRG\AddressBundle\Model;

use KRG\AddressBundle\Entity\AddressInterface;

class GooglePlaceModel
{
    /** @var array */
    private $data;

    /** @var array */
    private $normalizedData;

    /** @var AddressModel */
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

    /**
     * @return string
     */
    public function getFormattedAddress()
    {
        return isset($this->data['formatted_address']) ? $this->data['formatted_address'] : '';
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        if (isset($this->data['types'][0])) {
            switch ($this->data['types'][0]) {
                case 'country':
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
        }

        return null;
    }

    /**
     * @return CoordinatesModel|null
     */
    public function getCoordinates()
    {
        if (isset($this->data['coordinate']) || isset($this->data['geometry']['location'])) {
            $latitude = $this->data['coordinate']['latitude'] ?? $this->data['geometry']['location']['lat'];
            $longitude = $this->data['coordinate']['longitude'] ?? $this->data['geometry']['location']['lng'];

            return new CoordinatesModel($latitude, $longitude);
        }

        return null;
    }

    private function getProperty($property, $name)
    {
        if (!isset($this->normalizedData[$property])) {
            $this->normalizedData[$property] = array();
        } else if (isset($this->normalizedData[$property][$name])) {
            return $this->normalizedData[$property][$name];
        }

        if (isset($this->data['address_components'])) {
            foreach ($this->data['address_components'] as $component) {
                if ($component['types'][0] == $property) {
                    return $this->normalizedData[$property][$name] = $component[$name];
                }
            }
        }

        return $this->normalizedData[$property][$name] = null;
    }

    /**
     * @return AddressModel
     */
    public function getAddress()
    {
        if ($this->address instanceof AddressInterface) {
            return $this->address;
        }

        $this->address = new AddressModel();
        $this->address
            ->setCity($this->getProperty('locality', 'long_name'))
            ->setRegion($this->getProperty('administrative_area_level_1', 'long_name'))
            ->setDepartment($this->getProperty('administrative_area_level_2', 'long_name'))
            ->setPostalCode($this->getProperty('postal_code', 'long_name'))
            ->setAddress1($this->getProperty('route', 'long_name'));

        if ($this->getProperty('street_number', 'long_name') !== null) {
            $this->address->setAddress1($this->getProperty('street_number', 'long_name').' '.$this->getProperty('route', 'long_name'));
        }

        return $this->address;
    }

    /**
     * @return string
     */
    public function getVicinity()
    {
        if (key_exists('vicinity', $this->data)) {
            return $this->data['vicinity'];
        }

        return '';
    }
}
