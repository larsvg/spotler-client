<?php

namespace Spotler\Models;

use stdClass;

class Contact
{
    const CONTACT_CHANNEL_SMS = 'SMS';
    const CONTACT_CHANNEL_EMAIL = 'EMAIL';
    public string   $externalId;
    public string   $created;
    public string   $encryptedId;
    public bool     $testGroup = false;
    public string   $lastChanged;
    public string   $temporary;
    public stdClass $properties;
    public array    $channels  = [];



    public function __construct()
    {
        $this->properties              = new stdClass();
        $this->properties->permissions = [];
    }



    public function setPermission(int $bit, bool $enabled)
    {
        $found_key = array_search($bit, array_column($this->properties->permissions, 'bit'));
        if ($found_key !== false) {
            $this->properties->permissions[$found_key]['enabled'] = $enabled;
            return $this;
        }

        $permission                      = [];
        $permission['bit']               = $bit;
        $permission['enabled']           = $enabled;
        $this->properties->permissions[] = $permission;
        return $this;
    }



    public function setProperty(string $name, mixed $value)
    {
        $this->properties->{$name} = $value;
        return $this;
    }



    public function getProperty(string $name)
    {
        return $this->properties->{$name};
    }



    public function getPermissions()
    {
        return $this->properties->permissions;
    }



    public function setChannel(string $channelName, bool $value = true): void
    {
        $channel          = new stdClass();
        $channel->name    = $channelName;
        $channel->value   = $value;
        $this->channels[] = $channel;
    }
}