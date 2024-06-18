<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\User;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
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
    #[Route('/', name: 'user_profile')]
    public function profile(): Response
    {
        return $this->render('user/profile.html.twig');
    }

    #[Route('/collection', name: 'user_collection')]
    public function collection(AlbumRepository $repo, Security $security): Response
    {
        return $this->render('user/collection.html.twig', [
            "albums" => $repo->findBy(["user" => $security->getUser()->getId()])
        ]);
    }
// upload file
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
