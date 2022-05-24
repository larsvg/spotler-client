<?php

namespace Spotler\Modules;

use Spotler\Exceptions\SpotlerException;
use Spotler\Models\ContactRequest;
use stdClass;

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
    public function show(ContactRequest $contactRequest): ?stdClass
    {
        $response = $this->client->execute(
            'integrationservice-1.1.0/contact/' . $contactRequest->contact->externalId,
            'GET'
        );
        if ($this->client->getLastResponseCode() !== 200) {
            return null;
        }
        return $response;
    }



    public function inactive(ContactRequest $contactRequest): ?bool
    {
        try {
            $response = $this->client->execute(
                'integrationservice-1.1.0/contact/inactivate/' . $contactRequest->contact->externalId,
                'PUT'
            );
            if ($this->client->getLastResponseCode() !== 204) {
                return false;
            }
            return true;
        } catch (SpotlerException $e) {
            return null;
        }
    }
}
