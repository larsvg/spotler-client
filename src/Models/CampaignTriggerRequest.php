<?php

namespace Spotler\Models;

class CampaignTriggerRequest
{
    public ?string $externalContactId = null;
    public ?array  $campaignFields    = null;



    public function setCampaignField(CampaignField $campaignField)
    {
        $this->campaignFields[] = $campaignField;
        return $this;
    }



    public function getCampaignFields()
    {
        return $this->campaignFields;
    }
}