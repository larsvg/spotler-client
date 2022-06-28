<?php

use Spotler\Models\Contact;
use Spotler\Models\ContactRequest;
use Spotler\Services\Properties;
use Spotler\SpotlerClient;

$emailAddress = 'your-emailadres@provider.dot';
$externalId   = md5($emailAddress);

$contact = new Contact();
$contact->setChannel(Contact::CONTACT_CHANNEL_EMAIL);
$contact->externalId = $externalId;
$contact->setProperty('email', $emailAddress);

$contactRequest         = new ContactRequest();
$contactRequest->update = true;
$contactRequest->purge  = false;
$contactRequest->setContact($contact);

$spotlerConsumerKey    = env('spotlerConsumerKey');
$spotlerConsumerSecret = env('spotlerConsumerSecret');

$client  = new SpotlerClient($spotlerConsumerKey, $spotlerConsumerSecret);
$contact = $client->contact->show($contactRequest);


/**
 * Add contact if it does not exist
 */

if (empty($contact)) {
    if ($client->contact->add($contactRequest)) {
        $contact = $client->contact->show($contactRequest);
    }
}

/**
 * List all properties
 */

var_dump(Properties::getPermissions($client->contact->list())?->entries);


/**
 * Modify permissions
 */

$contactPermissions               = collect($contact->properties->permissions)->whereIn('bit', [1, 2]);
$contact->properties->permissions = Properties::modifyPermission($contactPermissions, 1, true);

var_dump($client->subscribe->subscribe($contact));


/**
 * Modify custom fields
 */

$contact->properties->profileField2 = 'yes';
$contact->properties->profileField1 = 'yes';

var_dump($client->subscribe->subscribe($contact));
