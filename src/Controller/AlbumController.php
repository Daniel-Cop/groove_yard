<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AlbumController extends AbstractController
{
    #[Route('/album', name: 'album_list')]
    public function list(AlbumRepository $repo): Response
    {
        return $this->render('album/list.html.twig', [
            'albums' => $repo->findBy([], ['createdAt' => 'DESC']),
        ]);
    }
}
