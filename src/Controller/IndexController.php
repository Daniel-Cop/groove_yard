<?php

namespace App\Controller;

use App\Entity\Address;
use App\Service\CoordinateApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('index/about.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }


    #[Route('/coordinate', name: 'user_coordinate')]
    public function coordinate(CoordinateApi $coordinateGetter): Response
    {
        $address = new Address();
        $address->setNumber('256')
            ->setStreet('Paul Bert')
            ->setCity('Lyon')
            ->setPostalCode('69003');

        $coordinate = $coordinateGetter->getCoordinate($address);

        return $this->render('index/about.html.twig', [
            'lat' => $coordinate['lat'],
            'lon' => $coordinate['lon']
        ]);
    }
}
