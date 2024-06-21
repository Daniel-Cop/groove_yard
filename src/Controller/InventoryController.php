<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Inventory;
use App\Form\InventoryType;
use App\Repository\IntentionRepository;
use App\Repository\InventoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


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
    public function add(Request $request, EntityManagerInterface $em, Security $security, Album $album)
    {
        $item = new Inventory();
        $form = $this->createForm(InventoryType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item->setUser($security->getUser())
                ->setAlbum($album)
                ->setCreatedAt(new \DateTime());
            $em->persist($item);
            $em->flush();


            return $this->redirectToRoute('album_item', ['id' => $album->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('inventory/add.html.twig', [
            'form' => $form,
            'album' => $album
        ]);
    }

    #[Route('profile/collection/edit/{id}', name: 'user_collection_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Inventory $item, EntityManagerInterface $em)
    {
        $form = $this->createForm(InventoryType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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
    public function market(InventoryRepository $repo): Response
    {
        $items = $repo->findByIntention('To Sell');

        return $this->render('album/market.html.twig', [
            'items' => $items
        ]);
    }

}
