<?php

namespace KRG\AddressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ivory\GoogleMap\Base\Coordinate;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperclass
 */
abstract class Address implements AddressInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address1;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address2;

    /**
     * @Assert\Regex(pattern="/^[0-9]{5}$/", message="address.postalCode.regexp")
     * @ORM\Column(type="string", nullable=true)
     */
    protected $postalCode;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", nullable=true)
     */
    protected $city;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    protected $department;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    protected $region;

    /**
     * @ORM\ManyToOne(targetEntity="KRG\AddressBundle\Entity\CountryInterface", cascade={"merge", "detach"})
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * @var Country
     */
    protected $country;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $formattedAddress;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $longitude;

    /**
     * @ORM\Column(type="boolean", name="is_approximate")
     */
    protected $approximate;

    public function __construct()
    {
        $this->latitude = 0;
        $this->longitude = 0;
        $this->approximate = true;
    }

    public function __toString()
    {
        $address = sprintf("%s %s %s %s %s, %s", $this->name, $this->address1, $this->address2, $this->postalCode, $this->city, (string) $this->country);

        return (string)preg_replace('/[\ ][\ ]+|\n\n+/', ' ', trim($address));
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Address
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address1
     *
     * @param string $address1
     *
     * @return Address
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1
     *
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     *
     * @return Address
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return Address
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Address
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set formattedAddress
     *
     * @param string $formattedAddress
     *
     * @return Address
     */
    public function setFormattedAddress($formattedAddress)
    {
        $this->formattedAddress = $formattedAddress;

        return $this;
    }

    /**
     * Get formattedAddress
     *
     * @return string
     */
    public function getFormattedAddress()
    {
        return $this->formattedAddress;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Address
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Address
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set country
     *
     * @param CountryInterface $country
     *
     * @return Address
     */
    public function setCountry(CountryInterface $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return CountryInterface
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set approximate
     *
     * @param boolean $approximate
     *
     * @return Address
     */
    public function setApproximate($approximate)
    {
        $this->approximate = $approximate;

        return $this;
    }

    /**
     * Get approximate
     *
     * @return boolean
     */
    public function isApproximate()
    {
        return $this->approximate;
    }

	/**
	 * @return string
	 */
	public function getDepartment()
	{
		return $this->department;
	}

	/**
	 * @param string $department
	 * @return $this
	 */
	public function setDepartment($department)
	{
		$this->department = $department;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getRegion()
	{
		return $this->region;
	}

	/**
	 * @param string $region
	 * @return $this
	 */
	public function setRegion($region)
	{
		$this->region = $region;
		return $this;
	}

	public function getCoordinate(){
		$coordinate = new Coordinate();

		$coordinate->setLatitude($this->getLatitude());
		$coordinate->setLongitude($this->getLongitude());

		return $coordinate;
	}
}
