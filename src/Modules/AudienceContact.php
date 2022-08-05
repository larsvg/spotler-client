<?php

namespace Spotler\Modules;

use Spotler\Exceptions\SpotlerException;
use Spotler\Models\ContactRequest;
use stdClass;

class AudienceContact extends AbstractModule
{
    /**
     * @throws SpotlerException
     */
    public function show(ContactRequest $contactRequest): bool
    {
        $response = $this->client->execute(
            'integrationservice/audience/' . $contactRequest->contact->externalId . '/contact',
            'POST',
            $contactRequest
        );
        if ($this->client->getLastResponseCode() == 204) {
            return true;
        }
        return false;
    }
}
