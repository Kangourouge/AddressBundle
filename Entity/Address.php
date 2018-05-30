<?php

namespace KRG\AddressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use KRG\AddressBundle\Model\Coordinates;
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
        $address = sprintf("%s %s %s %s %s, %s",
            $this->name,
            $this->address1,
            $this->address2,
            $this->postalCode,
            $this->city,
            (string)$this->country);

        return (string)preg_replace('/[\ ][\ ]+|\n\n+/', ' ', trim($address));
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * {@inheritdoc}
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * {@inheritdoc}
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormattedAddress($formattedAddress)
    {
        $this->formattedAddress = $formattedAddress;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormattedAddress()
    {
        return $this->formattedAddress;
    }

    /**
     * {@inheritdoc}
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * {@inheritdoc}
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * {@inheritdoc}
     */
    public function getCoordinate()
    {
        $coordinate = new Coordinates();
        $coordinate->setLatitude($this->getLatitude());
        $coordinate->setLongitude($this->getLongitude());

        return $coordinate;
    }

    /**
     * {@inheritdoc}
     */
    public function setCountry(CountryInterface $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * {@inheritdoc}
     */
    public function setApproximate($approximate)
    {
        $this->approximate = $approximate;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isApproximate()
    {
        return $this->approximate;
    }

    /**
     * {@inheritdoc}
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * {@inheritdoc}
     */
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * {@inheritdoc}
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }
}
