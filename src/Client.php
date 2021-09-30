<?php

namespace Velostazione\BeSMS;

class Client extends \GuzzleHttp\Client
{

    private const HOSTNAME = 'https://secure.apisms.it';

    public function __construct()
    {
        parent::__construct([
            'base_uri' => self::HOSTNAME
        ]);
    }
}
