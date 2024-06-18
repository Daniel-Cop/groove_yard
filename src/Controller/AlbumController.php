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

    // upload file per creazione album (admin)
//     #[Route("/album/new", name:"user_album_new")]
//     public function new(Request $request, EntityManagerInterface $em, Security $security, SluggerInterface $slugger): Response
//     {
//         $album = new Album();
//         $form = $this->createForm(AlbumType::class, $album);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $profilePic */
//             $image = $form->get('image')->getData();
//             if ($image) {

//                 $ogFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
//                 $safeFilename = $slugger->slug($ogFilename);
//                 $filename = $safeFilename.'-'.uniqid().'.'. $image->guessExtension();

//                 try {
//                     $image->move('uploads/albums/', $filename);
                    
//                     if ($album->getImage() !== null) {
//                         unlink(__DIR__ . "/../../public/uploads/album/" . $album->getImage());
//                     }

//                     $album->setImage($filename);
//                 } catch (FileException $e) {
//                     $form->addError(new FormError('Error during the upload'));
//                 }
//             }

//             $album->setCreatedAt(new \DateTime());
//             $album->setUser($security->getUser());
//             $em->persist($album);
//             $em->flush();

//             return $this->redirectToRoute("user_collection");
//         }

//         return $this->render('album/new.html.twig', [
//             'form' => $form,
//         ]);
//     }
}
