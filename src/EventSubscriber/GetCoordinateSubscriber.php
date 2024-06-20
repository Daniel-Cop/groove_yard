<?php

namespace App\EventSubscriber;

use App\Event\AddressRegisteredEvent;
use App\Service\CoordinateApi;
use App\Entity\Address;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

// In case of a custom event use AsEventListener which will take
// as agruments the name of the custom event and the name of the 
// method to call when the event is dispatched
#[AsEventListener(AddressRegisteredEvent::NAME, 'addressRegistered')]
class GetCoordinateSubscriber
{
    public function __construct(private CoordinateApi $coordinateGetter)
    {
    }
    public function addressRegistered(AddressRegisteredEvent $event): void
    {
        $entity = $event->getAddress();

        if (!$entity instanceof Address) {
            return;
        }

        $coordinates = $this->coordinateGetter->getCoordinate($entity);
        $entity->setLatitude($coordinates['lat']);
        $entity->setLongitude($coordinates['lon']);

    }
}