<?php

namespace Spotler\Modules;

use Spotler\Exceptions\SpotlerException;
use Spotler\Models\ContactRequest;
use stdClass;

class Contact extends AbstractModule
{
    public function add(ContactRequest $contactRequest): bool
    {
        try {
            $response = $this->client->execute('integrationservice-1.1.0/contact', 'POST', $contactRequest);
            if ($this->client->getLastResponseCode() == 204) {
                return true;
            }
            return false;
        } catch (SpotlerException $e) {
            return false;
        }
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
    public function search(string $email)
    {
        $response = $this->client->execute(
            'integrationservice/contact/search?pageSize=1&MPSearchQuery=email%3D' . $email,
            'GET',
        );

        if ($this->client->getLastResponseCode() !== 200) {
            return null;
        }

        return collect($response->contacts)->first();
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



    public function list()
    {
        $response = $this->client->execute('integrationservice-1.1.0/contact/properties/list', 'GET');
        if ($this->client->getLastResponseCode() == 200) {
            return $response;
        }
        return false;
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
