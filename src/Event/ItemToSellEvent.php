<?php

namespace App\Event;

use App\Entity\Inventory;
use Symfony\Contracts\EventDispatcher\Event;



class ItemToSellEvent extends Event
{

    // This event is dispatched when an item (inventory entity) is registrated 
    // (or edited) with 'To Sell' intention. 

    public const NAME = 'item.sell_listed';

    public function __construct(private Inventory $item)
    {
    }

    public function getItem(): Inventory
    {
        return $this->item;
    }
}