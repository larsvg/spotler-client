<?php

namespace Spotler\Modules;

use Spotler\Exceptions\SpotlerException;
use Spotler\Models\ContactRequest;

class Contact extends AbstractModule
{
    /**
     * @throws SpotlerException
     */
    public function add(ContactRequest $contactRequest): bool
    {
        $response = $this->client->execute('integrationservice-1.1.0/contact', 'POST', $contactRequest);
        if ($this->client->getLastResponseCode() == 204) {
            return true;
        }
        return false;
    }



    /**
     * @throws SpotlerException
     */
    public function update(ContactRequest $contactRequest): bool
    {
        $response = $this->client->execute('integrationservice-1.1.0/contact', 'PUT', $contactRequest);
        if ($this->client->getLastResponseCode() == 204) {
            return true;
        }
        return false;
    }



    /**
     * @throws SpotlerException
     */
    public function show(): ?array
    {
        $response = $this->client->execute('integrationservice-1.1.0/contact/properties/list', 'GET');
        if ($this->client->getLastResponseCode() == 200) {
            return $response;
        }
        return null;
    }
}
