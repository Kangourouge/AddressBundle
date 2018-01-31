<?php

namespace KRG\AddressBundle\Model;

final class Coordinates
{
    /**
     * @var double
     */
    private $latitude;

    /**
     * @var double
     */
    private $longitude;

    /**
     * @param double $latitude
     * @param double $longitude
     */
    public function __construct($latitude, $longitude)
    {
        $this->latitude  = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return double
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return double
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}
