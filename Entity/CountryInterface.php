<?php

namespace KRG\AddressBundle\Entity;

use EMC\FileinputBundle\Entity\FileInterface;

interface CountryInterface
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
     * Set flag
     *
     * @param FileInterface $flag
     *
     * @return Country
     */
    public function setFlag(FileInterface $flag);

    /**
     * Get flag
     *
     * @return FileInterface
     */
    public function getFlag();

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Country
     */
    public function setCode($code);

    /**
     * Get code
     *
     * @return string
     */
    public function getCode();

    /**
     * Set prefered
     *
     * @param string $prefered
     *
     * @return Country
     */
    public function setPrefered($prefered);

    /**
     * Get prefered
     *
     * @return string
     */
    public function getPrefered();

    /**
     * Is prefered
     *
     * @return string
     */
    public function isPrefered();

    /**
     * Set active
     *
     * @param string $active
     *
     * @return Country
     */
    public function setActive($active);

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive();

    /**
     * Is active
     *
     * @return bool
     */
    public function isActive();
}
