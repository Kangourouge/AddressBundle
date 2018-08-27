<?php

namespace KRG\AddressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperclass(repositoryClass="KRG\AddressBundle\Repository\NationalityRepository")
 */
class Nationality implements NationalityInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", unique=true)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="KRG\AddressBundle\Entity\CountryInterface", mappedBy="nationality")
     */
    protected $countries;

    public function __construct()
    {
        $this->countries = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return (string)$this->name;
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
    public function addCountry(CountryInterface $country)
    {
        if (false === $this->countries->contains($country)) {
            $this->countries[] = $country;
            $country->setNationality($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeCountry(CountryInterface $country)
    {
        $this->countries->removeElement($country);
    }

    /**
     * {@inheritdoc}
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        foreach ($this->getCountries() as $country) {
            if ($country->isActive()) {
                return true;
            }
        }

        return false;
    }
}
