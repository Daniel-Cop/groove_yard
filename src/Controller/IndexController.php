<?php

namespace App\Controller;

use App\Entity\Address;
use App\Repository\ConditionRepository;
use App\Service\CoordinateApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(CoordinateApi $coordinateGetter): Response
    {

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController'
        ]);
    }
    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('index/about.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }


    #[Route('/conditions', name: 'app_conditions')]
    public function conditions(ConditionRepository $repo): Response
    {
        $conditions = $repo->findAll();
        
        return $this->render('index/conditions.html.twig', [
            'conditions' => $conditions
        ]);
    }

    #[Route('/thanks', name: 'app_thanks')]
    public function thanks(): Response
    {
        return $this->render('index/thanks.html.twig');
    }
}
