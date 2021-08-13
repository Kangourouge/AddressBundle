<?php

namespace KRG\AddressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
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
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $name;

    /**
     * @Assert\NotBlank
     *
     * @ORM\Column(type="string", length=2)
     */
    protected $code;

    /**
     * @ORM\OneToOne(targetEntity="FileInterface::class", cascade={"persist", "merge", "detach"})
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=true)
     */
    protected $flag;

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): CountryInterface
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setFlag(?FileInterface $flag = null): CountryInterface
    {
        $this->flag = $flag;

        return $this;
    }

    public function getFlag(): FileInterface
    {
        return $this->flag;
    }

    public function setCode(string $code): CountryInterface
    {
        $this->code = $code;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
