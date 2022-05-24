<?php

namespace Spotler\Models;

class ContactRequest
{
    public bool    $update = false;
    public bool    $purge  = false;
    public Contact $contact;



    public function setContact(Contact $contact)
    {
        $this->contact = $contact;
        return $this;
    }



    public function getContact()
    {
        return $this->contact;
    }
}