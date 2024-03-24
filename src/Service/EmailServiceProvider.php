<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailServiceProvider
{
    private MailerInterface $mailer;

    /**
     * EmailServiceProvider constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string $subject
     * @param string $from
     * @param array $to
     * @param array $context
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function send(string $subject, string $from, array $to = [], array $context = [], string $path): void
    {
        try {
            $email = (new Email())
                ->from('indhuja31794@gmail.com')
                ->to(...$to)
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject($subject)
                ->text('Sending emails is fun again!')
                ->html('<p>Hi, Please find the attachment</p>');
            if (!empty($path)) {
                $email->attachFromPath($path, 'image');
            }
            $this->mailer->send($email);

//            $email = (new TemplatedEmail())
//                ->from($from)
//                ->to($to)
//                ->subject($subject)
//                ->htmlTemplate($template)
//                ->context($context);
//
//            $this->mailer->send($email);
        } catch (TransportException $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }


    /**
     * @param string $subject
     * @param string $to
     * @param array $context
     * @param string $templatePath
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendPaymentReceivedMail(string $subject,string $to,array $context = [],string $templatePath): void
    {
        try {
            $email = (new TemplatedEmail())
                ->from('indhuja31794@gmail.com')
                ->to($to)
                ->subject($subject);
//                ->text('Sending emails is fun again!');
            if(!empty($templatePath))
            {
                $email->htmlTemplate($templatePath);
                if(!empty($context))
                    $email ->context([
                        'appointment_ref_no' => $context['appointment_ref_no'],
                        'paid_date' => $context['paid_date'],
                        'first_name' => $context['first_name'],
                        'last_name' => $context['last_name'],
                        'amount' => $context['amount'],
                        'created_date' => $context['created_date']
                    ]);
            }
            $this->mailer->send($email);
        } catch (TransportException $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }


    /**
     * @param string $subject
     * @param array $to
     * @param string $from
     * @param array $context
     * @param string $templatePath
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendInquiryEmail(string $subject,string $from,array $to = [],array $context = [],string $templatePath): void
    {
        try {
            $email = (new TemplatedEmail())
                ->from($from)
                ->to(...$to)
                ->subject($subject);
            if(!empty($templatePath))
            {
                $email->htmlTemplate($templatePath);
                if(!empty($context))
                    $email ->context([
                        'date' => $context['date'],
                        'name' => $context['name'],
                        'message' => $context['message'],
                        'ml' => $context['ml'],
                        'subject' => $context['subject'],
                    ]);
            }
            $this->mailer->send($email);
        } catch (TransportException $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }

}