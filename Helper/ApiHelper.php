<?php

namespace KRG\AddressBundle\Helper;

class ApiHelper
{
    /** @var string */
    private $apiKey;

    /** @var array */
    private $libraries;

    /** @var string */
    private $locale;

    /** @var boolean */
    private $loaded;

    function __construct(string $apiKey, array $libraries, string $locale)
    {
        $this->apiKey = $apiKey;
        $this->locale = $locale;
        $this->libraries = $libraries;
        $this->loaded = false;
    }

    public function render($callback = null)
    {
        $output = null;
        if (false === $this->loaded) {
            $output = sprintf('<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=%s&libraries=%s&callback=%s"></script>',
                              $this->apiKey, implode(',', $this->libraries), $callback);
            $this->loaded = true;
        }

        return $output;
    }
}
