<?php

namespace KRG\AddressBundle\Entity;

use KRG\AddressBundle\Model\CoordinatesModel;

interface AddressInterface
{
	public function getId(): int;

	public function setAddress1(string $address1): self;

	public function getAddress1(): string;

	public function setAddress2(string $address2): self;

	public function getAddress2(): string;

	public function setPostalCode(string $postalCode): self;

	public function getPostalCode(): string;

	public function setCity(string $city): self;

	public function getCity(): string;

	public function setLongitude(float $longitude): self;

	public function getLongitude(): float;

	public function setLatitude(float $latitude): self;

	public function getLatitude(): float;

	public function setCountry(CountryInterface $country): self;

	public function getCountry(): CountryInterface;

	public function setApproximate(bool $approximate): self;

	public function isApproximate(): bool;

	public function getDepartment(): string;

	public function setDepartment(string $department): self;

	public function getRegion(): string;

	public function setRegion(string $region): self;

	public function getCoordinate(): CoordinatesModel;
}
