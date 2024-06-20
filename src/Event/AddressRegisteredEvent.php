<?php

namespace App\Event;

use App\Entity\Address;
use Symfony\Contracts\EventDispatcher\Event;



class AddressRegisteredEvent extends Event
{

    // This event is dispatched when a new address is registered
    // I create this event instead of using the prePersist event to avoid 
    // to call the API on the Fixture creation

    public const NAME = 'address.registered';

    public function __construct(private Address $address)
    {
    }

    public function getAddress(): Address
    {
        return $this->address;
    }
}