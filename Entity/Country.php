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
     * @ORM\ManyToOne(targetEntity="KRG\AddressBundle\Entity\NationalityInterface", inversedBy="countries", cascade={"persist", "merge", "detach"})
     * @ORM\JoinColumn(name="nationality_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $nationality;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     * @var boolean
     */
    protected $prefered;

    /**
     * @ORM\Column(type="boolean", options={"default" : 1})
     * @var boolean
     */
    protected $active;

    public function __construct()
    {
        $this->active = true;
        $this->prefered = false;
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
        return strtolower($this->code);
    }

    /**
     * {@inheritdoc}
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * {@inheritdoc}
     */
    public function setNationality(NationalityInterface $nationality)
    {
        $this->nationality = $nationality;

        $nationality->addCountry($this);
    }

    /**
     * {@inheritdoc}
     */
    public function setPrefered($prefered)
    {
        $this->prefered = $prefered;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrefered()
    {
        return $this->prefered;
    }

    /**
     * {@inheritdoc}
     */
    public function isPrefered()
    {
        return $this->getPrefered();
    }

    /**
     * {@inheritdoc}
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * {@inheritdoc}
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return $this->getActive();
    }
}
