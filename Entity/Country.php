<?php

namespace KRG\AddressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use EMC\FileinputBundle\Entity\FileInterface;

/**
 * @ORM\MappedSuperclass
 */
class Country implements CountryInterface
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
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=2)
     */
    protected $code;

    /**
     * @ORM\OneToOne(targetEntity="File", cascade={"merge", "detach"})
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=true)
     * @var FileInterface
     */
    protected $flag;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
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
     * @return Country
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
     * Set flag
     *
     * @param FileInterface|null $flag
     *
     * @return Country
     */
    public function setFlag(FileInterface $flag = null)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get flag
     *
     * @return FileInterface
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Country
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}
