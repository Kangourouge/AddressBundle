<?php

namespace KRG\AddressBundle\Entity;

use Ivory\GoogleMap\Base\Coordinate;

interface AddressInterface
{
	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId();

	/**
	 * Set address1
	 *
	 * @param string $address1
	 *
	 * @return Address
	 */
	public function setAddress1($address1);

	/**
	 * Get address1
	 *
	 * @return string
	 */
	public function getAddress1();

	/**
	 * Set address2
	 *
	 * @param string $address2
	 *
	 * @return Address
	 */
	public function setAddress2($address2);

	/**
	 * Get address2
	 *
	 * @return string
	 */
	public function getAddress2();

	/**
	 * Set postalCode
	 *
	 * @param string $postalCode
	 *
	 * @return Address
	 */
	public function setPostalCode($postalCode);

	/**
	 * Get postalCode
	 *
	 * @return string
	 */
	public function getPostalCode();

	/**
	 * Set city
	 *
	 * @param string $city
	 *
	 * @return Address
	 */
	public function setCity($city);

	/**
	 * Get city
	 *
	 * @return string
	 */
	public function getCity();

	/**
	 * Set longitude
	 *
	 * @param float $longitude
	 *
	 * @return Address
	 */
	public function setLongitude($longitude);

	/**
	 * Get longitude
	 *
	 * @return float
	 */
	public function getLongitude();

	/**
	 * Set latitude
	 *
	 * @param float $latitude
	 */
	public function setLatitude($latitude);

	/**
	 * Get latitude
	 *
	 * @return float
	 */
	public function getLatitude();

	/**
	 * Set country
	 *
	 * @param CountryInterface $country
	 *
	 * @return Address
	 */
	public function setCountry(CountryInterface $country);

	/**
	 * Get country
	 *
	 * @return CountryInterface
	 */
	public function getCountry();

	/**
	 * Set approximate
	 *
	 * @param boolean $approximate
	 *
	 * @return Address
	 */
	public function setApproximate($approximate);

	/**
	 * Get approximate
	 *
	 * @return boolean
	 */
	public function isApproximate();

	/**
	 * @return string
	 */
	public function getDepartment();

	/**
	 * @param string $department
	 * @return $this
	 */
	public function setDepartment($department);

	/**
	 * @return string
	 */
	public function getRegion();

	/**
	 * @param string $region
	 * @return $this
	 */
	public function setRegion($region);

	/**
	 * @return Coordinate
	 */
	public function getCoordinate();
}
