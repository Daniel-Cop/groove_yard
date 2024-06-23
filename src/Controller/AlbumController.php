<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\SearchType;
use App\Repository\AlbumRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class AlbumController extends AbstractController
{
    #[Route('/album', name: 'album_list')]
    public function list(AlbumRepository $repo, Request $request, PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $value = $form->get('query')->getData();

            $query = $repo->createQueryBuilder('a')
                ->where('a.title LIKE :title')
                ->orWhere('a.artist LIKE :artist')
                ->setParameter(':title', '%' . $value . '%')
                ->setParameter(':artist', '%' . $value . '%')
                ->orderBy('a.title', 'ASC')
                ->getQuery();
        } else {
            $query = $repo->createQueryBuilder('a')->orderBy('a.title', 'ASC')->getQuery();
        }

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9 // number per page
        );
        return $this->render('album/list.html.twig', [
            'pagination' => $pagination,
            'form' => $form
        ]);
    }

    #[Route('/album/{id}', name: 'album_item')]
    public function item(Album $album): Response
    {
        return $this->render('album/item.html.twig', ['album' => $album]);
    }

}
