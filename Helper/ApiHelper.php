<?php

namespace KRG\AddressBundle\Helper;

class ApiHelper implements ApiHelperInterface
{
    /** @var string */
    protected $apiKey;

    /** @var array */
    protected $libraries;

    /** @var string */
    protected $locale;

    /** @var boolean */
    protected $loaded;

    function __construct(string $apiKey, array $libraries, string $locale)
    {
        $this->apiKey = $apiKey;
        $this->libraries = $libraries;
        $this->locale = $locale;
        $this->loaded = false;
    }

    public function render($callback = null, $async = true, $defer = true)
    {
        if (false === $this->loaded) {
            $this->loaded = true;

            return sprintf(
                '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=%s&libraries=%s&language=%s&callback=%s"%s%s></script>',
                $this->apiKey,
                implode(',', $this->libraries),
                $this->locale,
                $callback,
                $async ? ' async' : '',
                $defer ? ' defer' : ''
            );
        }

        return '';
    }
}
