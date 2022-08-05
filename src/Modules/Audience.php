<?php

namespace Spotler\Modules;

use Spotler\Exceptions\SpotlerException;
use Spotler\Models\ContactRequest;
use stdClass;

class Audience extends AbstractModule
{
    /**
     * @throws SpotlerException
     */
    public function show(ContactRequest $contactRequest): ?stdClass
    {
        $response = $this->client->execute(
            'integrationservice/audience/' . $contactRequest->contact->externalId,
            'GET'
        );
        if ($this->client->getLastResponseCode() !== 200) {
            return null;
        }
        return $response;
    }
}
