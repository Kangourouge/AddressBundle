<?php

namespace KRG\AddressBundle\Entity;

interface NationalityInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Country
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Add country
     *
     * @param CountryInterface $country
     *
     * @return Nationality
     */
    public function addCountry(CountryInterface $country);

    /**
     * Remove country
     *
     * @param CountryInterface $country
     */
    public function removeCountry(CountryInterface $country);

    /**
     * Get countries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCountries();
}
