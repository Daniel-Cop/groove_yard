<?php

namespace App\EventSubscriber;

use App\Entity\Inventory;
use App\Event\ItemToSellEvent;
use App\Mail\ItemToSellNotification;
use App\Repository\InventoryRepository;
use App\Service\DistanceCalculator;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;


#[AsEventListener(ItemToSellEvent::NAME, 'notifyUser')]
class UserNotifierSubscriber
{
    private const RANGE = 50; // in kilometers

    public function __construct(
        private InventoryRepository $repo,
        private ItemToSellNotification $mailer,
        private DistanceCalculator $distanceCalculator
        )
    {
    }
    public function notifyUser(ItemToSellEvent $event): void
    {
        $entity = $event->getItem();

        if (!$entity instanceof Inventory) {
            return;
        }

        if ($entity->getIntention()->getName() !== 'To Sell') {
            return;
        }
        
        $sellerLat = $entity->getUser()->getAddress()->getLatitude();
        $sellerLon = $entity->getUser()->getAddress()->getLongitude();
        $items = $this->repo->findByAlbumToSell('Want', $entity->getAlbum());
        $users = [];
        foreach ($items as $item) {
            $users[] = $item->getUser();
        }

        foreach ($users as $user) {
            
            $boundingBox =  $this->distanceCalculator->getBoundingBox($user->getAddress(), self::RANGE);
            
            $isUserInBoundingBox = (
                $boundingBox['minLat'] < $sellerLat && 
                $boundingBox['maxLat'] > $sellerLat &&
                $boundingBox['minLon'] < $sellerLon &&
                $boundingBox['maxLon'] > $sellerLon
            );

            // the email will be sent just to the users for whom the seller 
            // is in the chose range (50 km)
            if ($isUserInBoundingBox){
                $this->mailer->sendItemNotification($user);
            }
        }
    }
}