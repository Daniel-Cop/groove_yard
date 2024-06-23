<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Inventory;
use App\Form\InventoryType;
use App\Form\SearchType;
use App\Repository\IntentionRepository;
use App\Repository\InventoryRepository;
use App\Service\DistanceCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Event\ItemToSellEvent;


class InventoryController extends AbstractController
{
    #[Route('profile/collection', name: 'user_collection', methods: ['GET'])]
    public function collection(InventoryRepository $repo, Security $security): Response
    {
        $items = $repo->findBy(['user' => $security->getUser()->getId()]);
        $owned = [];
        $toSell = [];
        $want = [];
        foreach ($items as $item) {
            switch ($item->getIntention()) {
                case "Owned":
                    $owned[] = $item;
                    break;
                case "To Sell":
                    $toSell[] = $item;
                    break;
                case "Want":
                    $want[] = $item;
                    break;
                default:
            }
        }
        return $this->render('user/collection.html.twig', [
            "owned" => $owned,
            "to_sell" => $toSell,
            "want" => $want
        ]);
    }

    #[Route('profile/collection/add/{id}', name: 'user_collection_add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $em, Security $security, Album $album,EventDispatcherInterface $dispatcher)
    {
        $item = new Inventory();
        $form = $this->createForm(InventoryType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item->setUser($security->getUser())
                ->setAlbum($album)
                ->setCreatedAt(new \DateTime());

                if ($item->getIntention()->getName() == 'To Sell') {
                    $event = new ItemToSellEvent($item);
                    $dispatcher->dispatch($event, ItemToSellEvent::NAME);
                }

            $em->persist($item);
            $em->flush();


            return $this->redirectToRoute('user_collection');
        }

        return $this->render('inventory/add.html.twig', [
            'form' => $form,
            'album' => $album
        ]);
    }


    #[Route('profile/collection/edit/{id}', name: 'user_collection_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Inventory $item, EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $form = $this->createForm(InventoryType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {   
            
            if ($item->getIntention()->getName() == 'To Sell') {
                $event = new ItemToSellEvent($item);
                $dispatcher->dispatch($event, ItemToSellEvent::NAME);
            }
            
            
            $em->flush();

            return $this->redirectToRoute('user_collection', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('inventory/edit.html.twig', [
            'form' => $form,
            "item" => $item
        ]);
    }

    #[Route('profile/collection/delete/{id}', name: 'user_collection_delete', methods: ['POST'])]
    public function delete(Request $request, Inventory $item, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $item->getId(), $request->getPayload()->get('_token'))) {
            $em->remove($item);
            $em->flush();
        }
        return $this->redirectToRoute('user_collection', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/market', name: 'album_market')]
    public function market(InventoryRepository $repo, Request $request, PaginatorInterface $paginator, DistanceCalculator $calculator, Security $security): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        // search field default value
        $searchValue = '';
        $distance = 600;

        if ($form->isSubmitted() && $form->isValid()) {
            
            $searchValue = $form->get('query')->getData();
            
            if ($searchValue === null) {
                $searchValue = "";
            }
            
            // if the user didn't chose "everywhere"
            if ($form->get('distance')->getData() !== null) {               
                $distance = $form->get('distance')->getData();
            }
        }
        
        $boundingBox = $calculator->getBoundingBox($security->getUser()->getAddress(), $distance);

        $query = $repo->findByMarketFilter('To Sell', $boundingBox, $security->getUser()->getId(), $searchValue);
        $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
        10 // number per page
        );

        return $this->render('album/market.html.twig', [
        'pagination' => $pagination,
        'form' => $form
        ]);
    }

}
