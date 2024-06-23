<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Mail\UserRegisteredConfirmation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;


class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');

    }

    #[Route(path: '/register', name: 'app_register')]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        Security $security,
        UserRegisteredConfirmation $mailer,
        SluggerInterface $slugger
    ) {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $img */
            $img = $form->get('image')->getData();
            if ($img) {

                $ogFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($ogFilename);
                $filename = $safeFilename . '-' . uniqid() . '.' . $img->guessExtension();

                try {
                    $img->move('uploads/user/', $filename);

                    if ($user->getImage() !== null) {
                        unlink(__DIR__ . "/../../public/uploads/user/" . $user->getImage());
                    }

                    $user->setImage($filename);
                } catch (FileException $e) {
                    $form->addError(new FormError('Error during the upload'));
                }
            }

            $address = $user->getAddress();
            $address->addUser($user);

            
            $em->persist($address);
            $em->persist($user);

            $mailer->sendRegistrationConfirmation($user);
            
            $em->flush();

            $security->login($user);

            return $this->redirectToRoute('app_index');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form,
            'user' => $user
        ]);
    }

}
