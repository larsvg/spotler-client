<?php

namespace Spotler\Modules;

use Spotler\Exceptions\SpotlerException;
use Spotler\Models\CampaignTriggerRequest;

class Campaign extends AbstractModule
{
    /**
     * @throws SpotlerException
     */
    public function getList()
    {
        $response = $this->client->execute('integrationservice/campaign/list', 'GET');
        return $response;
    }



    /**
     * @throws SpotlerException
     */
    public function postCampaignTrigger($encryptedTriggerId, CampaignTriggerRequest $campaignTriggerRequest): bool
    {
        $response = $this->client->execute(
            'integrationservice/campaign/trigger/' . $encryptedTriggerId,
            'POST',
            $campaignTriggerRequest
        );
        if ($this->client->getLastResponseCode() == 204) {
            return true;
        }
        return false;
    }
}
