<?php

namespace Spotler\Modules;

use Spotler\Exceptions\SpotlerException;
use Spotler\Models\ContactRequest;
use stdClass;

class Subscribe extends AbstractModule
{
    /**
     * @throws SpotlerException
     */
    public function subscribe($contactRequest): bool
    {
        $response = $this->client->execute('integrationservice-1.1.0/subscription/subscribe', 'POST', $contactRequest);
        if ($this->client->getLastResponseCode() == 204) {
            return true;
        }
        return false;
    }
}
