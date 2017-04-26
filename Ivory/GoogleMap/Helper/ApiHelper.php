<?php

namespace KRG\AddressBundle\Ivory\GoogleMap\Helper;

use Ivory\GoogleMap\Helper\ApiHelper as BaseApiHelper;

class ApiHelper extends BaseApiHelper
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

    function __construct($apiKey, array $libraries, $locale)
    {
        $this->apiKey = $apiKey;
        $this->libraries = $libraries;
        $this->locale = $locale;

        parent::__construct();
    }

    public function render($language = 'en', array $libraries = array(), $callback = null, $sensor = false)
    {
        $otherParameters = array();

        $otherParameters['libraries'] = implode(',', $this->libraries);

        $otherParameters['key'] = $this->apiKey;
        $otherParameters['language'] = $this->locale;
        $otherParameters['sensor'] = json_encode((bool) $sensor);

        $this->jsonBuilder
            ->reset()
            ->setValue('[other_params]', urldecode(http_build_query($otherParameters)));

        if ($callback !== null) {
            $this->jsonBuilder->setValue('[callback]', $callback, false);
        }

        $callbackFunction = 'load_ivory_google_map_api';
        $url = sprintf('//www.google.com/jsapi?callback=%s', $callbackFunction);
        $loader = sprintf('google.load("maps", "3", %s);', $this->jsonBuilder->build());

        $output = array();
        $output[] = '<script type="text/javascript">'.PHP_EOL;
        $output[] = sprintf('function %s () { %s };'.PHP_EOL, $callbackFunction, $loader);
        $output[] = '</script>'.PHP_EOL;
        $output[] = sprintf('<script type="text/javascript" src="%s"></script>'.PHP_EOL, $url);

        $this->loaded = true;

        return implode('', $output);
    }

}
