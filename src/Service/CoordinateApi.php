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

    public function getCoordinate(Address $address): array
    {
        $street = $address->getNumber() . '+' . $address->getStreet();
        $postalCode = $address->getPostalCode();
        $city = $address->getCity();

        $response = $this->coordinateGetter->request('GET', '/search', [
            'query' => [
                'street' => $street,
                'postalcode' => $postalCode,
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

}