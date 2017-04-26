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
}
