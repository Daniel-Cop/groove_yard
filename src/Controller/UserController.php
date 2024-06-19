<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Inventory;
use App\Entity\User;
use App\Form\AlbumType;
use App\Form\InventoryType;
use App\Repository\AlbumRepository;
use App\Repository\IntentionRepository;
use App\Repository\InventoryRepository;
use App\Service\CoordinateApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route("/profile")]
class UserController extends AbstractController
{
    // questa diventera modifica profilo
    #[Route('/', name: 'user_profile')]
    public function profile(): Response
    {
        return $this->render('user/profile.html.twig');
    }

}
