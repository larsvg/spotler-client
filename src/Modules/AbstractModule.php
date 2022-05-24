<?php

namespace Spotler\Modules;

use Spotler\SpotlerClient;

class AbstractModule
{
    protected SpotlerClient $client;



    public function __construct(SpotlerClient $client)
    {
        $this->client = $client;
    }
}