<?php

namespace App\Service;
use App\Entity\Address;

class DistanceCalculator {   
            
            // I use this service for business logic. It uses radians instead of flat distances to account for 
            // the curvature of the Earth's surface. (Perhaps overkill given that the prototype takes 
            // coordinates in France as examples, but technically more correct)
            // It take an address and a range and it calculate a range of minimun and maximum latitude and
            // longitude which represent the given distance from the original coordinates 


        private const EARTH_RADIUS = 6371; // in Kilometers (f*** you americans)
            
        public function getBoundingBox(Address $address, int $radius) // radius to be given in kilometers
        {   
            // approximate method: treat Earth as a perfect sphere assume that one degree of latitude is 111.11 km
            $deltaDeg = $radius / 111.11; // convert range to degrees

            $minLat = $address->getLatitude() - $deltaDeg;
            $maxLat = $address->getLatitude() + $deltaDeg;
            $minLon = $address->getLongitude() - abs($radius / (2*111.11 * cos($address->getLatitude()))/2);
            $maxLon = $address->getLongitude() + abs($radius / (2*111.11 * cos($address->getLatitude()))/2);


            // this should be a more precise method, but it gives me unprecise results
            // Convert distance to radians
            // $radRadius = $radius / self::EARTH_RADIUS;


    
            // // Calculate offsets for latitude and longitude based on radius
            // $radLatOffset = asin(sin($radRadius / 2));
            // $radLonOffset = rad2deg(asin(sin($radRadius / 2) / cos($address->getLatitude())));
    
            // $minLat = $address->getLatitude() - abs(rad2deg($radLatOffset))*2;
            // $maxLat = $address->getLatitude() + abs(rad2deg($radLatOffset))*2;
            // $minLon = $address->getLongitude() - abs($radLonOffset);
            // $maxLon = $address->getLongitude() + abs($radLonOffset);
    

            return [
                'minLat' => $minLat,
                'maxLat' => $maxLat,
                'minLon' => $minLon,
                'maxLon' => $maxLon
            ];
        }
    
        // I leave here this code for future references. It was my first solution, calculating the distance
        // betwee 2 given addressess. the problem was that it was too memory intensive, since it would run this 
        // code across all users during a search, while the Bounding Box method will run once for user and then 
        // the only thing to do is just a search in the database based on the latitude and longitude range
        

    // public function calculateDistance(Address $address1, Address $address2) {
        //     // convert degrees to radians
        //     $radLat1 = deg2rad(floatval($address1->getLatitude()));
        //     $radLon1 = deg2rad(floatval($address1->getLongitude()));
        //     $radLat2 = deg2rad(floatval($address2->getLatitude()));
        //     $radLon2 = deg2rad(floatval($address2->getLongitude()));
        
        //     $deltaLat = $radLat2 - $radLat1;
        //     $deltaLon = $radLon2 - $radLon1;
        
        //     $a = sin($deltaLat / 2) * sin($deltaLat /2) + cos($radLat1) * cos($radLat2) * sin($deltaLon / 2) * sin($deltaLon / 2);
        //     $c = 2 * asin(sqrt($a));
        
        //     return $c * self::EARTH_RADIUS;
        // }
}