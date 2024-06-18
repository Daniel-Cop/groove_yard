<?php

namespace App\Controller;

use App\Entity\Album;
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
            'albums' => $repo->findBy([], ['year' => 'DESC']),
        ]);
    }

    #[Route('/album/{id}', name: 'album_item')]
    public function item(Album $album): Response
    {
        return $this->render('album/item.html.twig', ['album' => $album]);
    }
}
