<?php

namespace KRG\AddressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use KRG\AddressBundle\Model\CoordinatesModel;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperclass
 */
class Address implements AddressInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $name;

    /**
     * @Assert\NotBlank(groups={"Localize"})
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $address1;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $address2;

    /**
     * @Assert\NotBlank(groups={"Localize"})
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $postalCode;

    /**
     * @Assert\NotBlank(groups={"Localize"})
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $city;

    /**
     * @Assert\NotBlank(groups={"Localize"})
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $department;

    /**
     * @Assert\NotBlank(groups={"Localize"})
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $region;

    /**
     * @ORM\ManyToOne(targetEntity="CountryInterface::class", fetch="EAGER")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     *
     * @var CountryInterface
     */
    protected $country;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $formattedAddress;

    /**
     * @Assert\NotBlank(groups={"Localize"})
     * @ORM\Column(type="float", nullable=true)
     *
     * @var float
     */
    protected $latitude;

    /**
     * @Assert\NotBlank(groups={"Localize"})
     * @ORM\Column(type="float", nullable=true)
     *
     * @var float
     */
    protected $longitude;

    /**
     * @ORM\Column(type="boolean", name="is_approximate")
     *
     * @var bool
     */
    protected $approximate;

    public function __construct()
    {
        $this->latitude = 0;
        $this->longitude = 0;
        $this->approximate = true;
    }

    public function __toString(): string
    {
        $address = sprintf("%s %s %s %s %s, %s",
            $this->name,
            $this->address1,
            $this->address2,
            $this->postalCode,
            $this->city,
            (string) $this->country
        );

        return (string) preg_replace('/[\ ][\ ]+|\n\n+/', ' ', trim($address));
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setAddress1(string $address1): AddressInterface
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getAddress1(): string
    {
        return $this->address1;
    }

    public function setAddress2(string $address2): AddressInterface
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getAddress2(): string
    {
        return $this->address2;
    }

    public function setPostalCode(string $postalCode): AddressInterface
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setCity(string $city): AddressInterface
    {
        $this->city = $city;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setFormattedAddress(string $formattedAddress): self
    {
        $this->formattedAddress = $formattedAddress;

        return $this;
    }

    public function getFormattedAddress(): string
    {
        return $this->formattedAddress;
    }

    public function setLongitude(float $longitude): AddressInterface
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLatitude(float $latitude): AddressInterface
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setCountry(CountryInterface $country): AddressInterface
    {
        $this->country = $country;

        return $this;
    }

    public function getCountry(): CountryInterface
    {
        return $this->country;
    }

    public function setApproximate(bool $approximate): AddressInterface
    {
        $this->approximate = $approximate;

        return $this;
    }

    public function isApproximate(): bool
    {
        return $this->approximate;
    }

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function setDepartment(string $department): AddressInterface
    {
        $this->department = $department;

        return $this;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function setRegion(string $region): AddressInterface
    {
        $this->region = $region;

        return $this;
    }

    public function getCoordinate(): CoordinatesModel
    {
        return new CoordinatesModel($this->getLatitude(), $this->getLongitude());
    }
}
