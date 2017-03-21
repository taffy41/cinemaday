<?php

namespace AppBundle\Classes;

use GuzzleHttp\Client;

/**
 * Class CineworldClient
 */
class CineworldClient
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param string $host
     * @param string $key
     */
    public function __construct($host, $key)
    {
        $this->host = $host;
        $this->key = $key;

        $this->client = new Client(
            [
                'base_uri' => $this->host,
                'content-type' => 'application/json',
                'headers' => [
                    'User-Agent' => ''
                ],
                'cookies' => true
            ]
        );
    }

    /**
     * @param string $route
     * @param array  $query
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get($route, $query = [])
    {
        $defaultQuery =  [
            'key' => $this->key,
            'full' => 'true'
        ];


        return $this->client->get(
            $route,
            [
                'query' => array_merge(
                    $defaultQuery,
                    $query
                )
            ]
        );
    }
}
