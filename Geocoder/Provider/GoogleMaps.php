<?php

namespace KRG\AddressBundle\Geocoder\Provider;

use Exception;
use Geocoder\Exception\InvalidCredentials;
use Geocoder\Exception\NoResult;
use Geocoder\Exception\QuotaExceeded;
use Geocoder\Provider\GoogleMaps as BaseGoogleMaps;
use Ivory\HttpAdapter\HttpAdapterInterface;

class GoogleMaps extends BaseGoogleMaps
{

    /**
     * @var string
     */
    private $region;

    /**
     * @var bool
     */
    private $useSsl;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @param HttpAdapterInterface $adapter An HTTP adapter
     * @param string               $locale  A locale (optional)
     * @param string               $region  Region biasing (optional)
     * @param bool                 $useSsl  Whether to use an SSL connection (optional)
     * @param string               $apiKey  Google Geocoding API key (optional)
     */
    public function __construct(HttpAdapterInterface $adapter, $locale = null, $region = null, $useSsl = false, $apiKey = null)
    {
        parent::__construct($adapter);

        $this->locale = $locale;
        $this->region = $region;
        $this->useSsl = $useSsl;
        $this->apiKey = $apiKey;
    }

    /**
     * {@inheritDoc}
     */
    public function search($address)
    {
        // Google API returns invalid data if IP address given
        // This API doesn't handle IPs
        if (filter_var($address, FILTER_VALIDATE_IP)) {
            throw new UnsupportedOperation('The GoogleMaps provider does not support IP addresses, only street addresses.');
        }

        $query = sprintf(
            $this->useSsl ? self::ENDPOINT_URL_SSL : self::ENDPOINT_URL,
            rawurlencode($address)
        );

        return $this->executeQuery($query);
    }

    /**
     * @param string $query
     */
    private function executeQuery($query)
    {
        $query   = $this->buildQuery($query);
        $content = (string) $this->getAdapter()->get($query)->getBody();

        // Throw exception if invalid clientID and/or privateKey used with GoogleMapsBusinessProvider
        if (strpos($content, "Provided 'signature' is not valid for the provided client ID") !== false) {
            throw new InvalidCredentials(sprintf('Invalid client ID / API Key %s', $query));
        }

        if (empty($content)) {
            throw new NoResult(sprintf('Could not execute query "%s".', $query));
        }

        $json = json_decode($content);

        // API error
        if (!isset($json)) {
            return sprintf('Could not execute query "%s".', $query);
        }

        if ('REQUEST_DENIED' === $json->status && 'The provided API key is invalid.' === $json->error_message) {
            return sprintf('API key is invalid %s', $query);
        }

        if ('REQUEST_DENIED' === $json->status) {
            return sprintf('API access denied. Request: %s - Message: %s', $query, $json->error_message);
        }

        // you are over your quota
        if ('OVER_QUERY_LIMIT' === $json->status) {
            return sprintf('Daily quota exceeded %s', $query);
        }

        // no result
        if (!isset($json->results) || !count($json->results) || 'OK' !== $json->status) {
            return sprintf('Could not execute query "%s".', $query);
        }

        return (array)$json;
    }
}
