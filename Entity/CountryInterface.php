<?php

namespace KRG\AddressBundle\Entity;

interface CountryInterface
{
    public function getId(): int;

    public function setName(string $name): self;

    public function getName(): string;

    public function setFlag(?FileInterface $flag): self;

    public function getFlag(): FileInterface;

    public function setCode(string $code): self;

    public function getCode(): string;
}
