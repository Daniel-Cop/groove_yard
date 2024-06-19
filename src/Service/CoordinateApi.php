<?php

namespace App\Service;

use App\Entity\Address;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoordinateApi
{
    public function __construct(
        private HttpClientInterface $coordinateGetter
    ) {
    }
    // Generate latitude and longitude from a specific address via the Nominatim API
    public function getCoordinate(Address $address): array
    {
        $street = $address->getNumber() . '+' . $address->getStreet();
        $postalCode = $address->getPostalCode();
        $city = $address->getCity();

        $response = $this->coordinateGetter->request('GET', '/search', [
            'query' => [
                'street' => $street,
                'postcode' => $postalCode,
                'city' => $city,
                'format' => 'json'
            ]
        ]);
        $data = $response->toArray();
        if (count($data) != 0) {
            $address = $data[0];
        }
        $coordinates = [
            'lat' => $address['lat'],
            'lon' => $address['lon'],
        ];
        return $coordinates;

    }

    // I leave that method for later consultation in case of need. It was created to make better fixture,
    // but later abandoned (see comment in AppFixture for further info)

    // public function reverse(Address $address): array
    // {
    //     $lat = $address->getLatitude();
    //     $lon = $address->getLongitude();

    //     $response = $this->coordinateGetter->request('GET', '/reverse', [
    //         'query'=> [
    //             'lat' => $lat,
    //             'lon' => $lon,
    //             'format' => 'json'
    //         ]
    //         ]);
    //     $data = $response->toArray();
    //     if (count($data) != 0) {
    //         $reversedAddress = $data['address'];
    //     }

    //     $newAddress = [
    //         'city' =>$reversedAddress['village']
    //     ];
    //     return $newAddress;
    // }

}