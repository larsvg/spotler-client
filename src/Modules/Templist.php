<?php

namespace Spotler\Modules;

use Spotler\Exceptions\SpotlerException;
use Spotler\Models\CampaignTriggerRequest;

class Templist extends AbstractModule
{
    public function all(): array
    {
        return $this->client->execute('integrationservice-1.1.0/templist', 'GET');
    }
}
