<?php

namespace KRG\AddressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EMC\FileinputBundle\Entity\FileInterface;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\OneToOne(targetEntity="EMC\FileinputBundle\Entity\FileInterface", cascade={"persist", "merge", "detach"})
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=true)
     * @var FileInterface
     */
    protected $flag;

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->name;
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
    public function setFlag(FileInterface $flag = null)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * {@inheritdoc}
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return $this->code;
    }
}
