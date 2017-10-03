<?php

namespace KRG\AddressBundle\Helper;

class ApiHelper
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var array
     */
    private $libraries;

    /**
     * @var string
     */
    private $locale;

    private $isLoaded = false;

    function __construct($apiKey, array $libraries, $locale)
    {
        $this->apiKey = $apiKey;
        $this->libraries = $libraries;
        $this->locale = $locale;
    }

    public function render()
    {
        if (false === $this->isLoaded) {
            $this->isLoaded = true;

            return sprintf('<script src="https://maps.googleapis.com/maps/api/js?key=%s&language=%s&libraries=%s" async defer></script>',
                $this->apiKey,
                $this->locale,
                implode(',', $this->libraries));
        }

        return '';
    }
}
