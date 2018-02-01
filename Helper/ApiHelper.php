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

    /**
     * @var boolean
     */
    private $loaded;

    function __construct($apiKey, array $libraries, $locale)
    {
        $this->apiKey = $apiKey;
        $this->locale = $locale;
        $this->libraries = $libraries;
    }

    public function render($language = 'en', array $libraries = array(), $callback = null, $sensor = false)
    {
        $libraries = implode(',', $this->libraries);
        $sensor = json_encode((bool) $sensor);
        $output = sprintf('<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=%s&libraries=%s&callback=%s" async defer></script>', $this->apiKey, $libraries, $callback);
        $this->loaded = true;

        return $output;
    }

    public function isLoaded()
    {
        return $this->loaded;
    }
}
