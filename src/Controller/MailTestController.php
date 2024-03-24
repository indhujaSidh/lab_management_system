<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailTestController extends AbstractController
{
    #[Route('/mailer', name: 'app_mailer')]
    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('indhuja17sidh@gmail.com')
            ->to('indhuja31794@gmail.com') // Primary recipient
            ->subject('Your subject here')
            ->text('This is the text version of the email.')
            ->html('<p>This is the HTML version of the email.</p>');

        $mailer->send($email);

        return new Response('Email was sent successfully.');
    }
}