<?php 

namespace App\Mail;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class UserRegisteredConfirmation {

    public function __construct(
        private MailerInterface $mailer,
        private string $adminEmail
    ) {}

    public function sendRegistrationConfirmation(User $user){
        $email = (new TemplatedEmail())
            ->from($this->adminEmail)
            ->to($user->getEmail())
            ->subject("Weclome to Groove Yard!")
            ->htmlTemplate('emails/welcome.html.twig')
            ->context([
                'user' => $user,
            ]);

            $this->mailer->send($email);
    }
}