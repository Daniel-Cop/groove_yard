<?php 

namespace App\Mail;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class ItemToSellNotification {

    public function __construct(
        private MailerInterface $mailer,
        private string $adminEmail
    ) {}

    public function sendItemNotification(User $user){
        $email = (new TemplatedEmail())
            ->from($this->adminEmail)
            ->to($user->getEmail())
            ->subject("We found one!")
            ->htmlTemplate('emails/notification.html.twig')
            ->context([
                'user' => $user,
            ]);

            $this->mailer->send($email);
    }
}