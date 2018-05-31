<?php

namespace KRG\AddressBundle\Intl;

use Doctrine\ORM\EntityManager;
use KRG\AddressBundle\Entity\CountryInterface;
use Symfony\Component\Intl\Intl;

class Language
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * TaskType constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getLocales()
    {
        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository(CountryInterface::class);

        $data = $repository
            ->createQueryBuilder('c')
            ->select('c.code')
            ->addOrderBy('c.name', 'ASC')
            ->getQuery()
            ->getScalarResult();

        $countries = array_column($data, 'code');

        $locales = array_keys(Intl::getLocaleBundle()->getLocaleNames());
        $languages = [];

        foreach ($locales as $locale) {
            if (strpos($locale, '_') === 2) {
                $language = substr($locale, 0, 2);
                if (!isset($languages[$language]) && in_array(substr($locale, 3), $countries)) {
                    $languages[$language] = \Locale::getDisplayName($language);
                }
            }
        }

        return $languages;
    }
}