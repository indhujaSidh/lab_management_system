<?php

namespace App\Controller\Transaction;


use App\Entity\Appointment\Appointment;
use App\Entity\ProcessStatus;
use App\Entity\Transaction\Transaction;
use App\Service\EmailServiceProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


#[Route('/transaction')]
class TransactionController extends AbstractController
{
    const transactionIdentificationCodePrefix = 'hiru@8a984d42d999fb0dc50c943c3b1701933c6fbe3cd8f6a278983c7a16f673eab79abd675fb7a035eb3064b42cb36fd3e699b9851839102bd5f5ec9af1f153feb247dd';


    #[Route('/pay/{id}', name: 'transaction_level_payment_pay', defaults: ['_signed' => 'true'], methods: 'GET')]
    public function index(ManagerRegistry $doctrine, ParameterBagInterface $parameterBag, $id)
    {
        $em = $doctrine->getManager();
        $transaction = new Transaction();
        $appointment = $em->getRepository(Appointment::class)->find($id);
        $transaction->setAppointmentId($appointment);
        $transaction->setAmount($appointment->getAmount());
        $transaction->setIdentification(hash('sha512', self::transactionIdentificationCodePrefix . (new \DateTime())->format('YmdHis')));
        $em->persist($transaction);
        $em->flush();
        $hash = $parameterBag->get('payhere_merchant_id');
        $hash .= $transaction->getId();
        $hash .= number_format($transaction->getAmount(), 2, '.', '');
        $hash .= 'LKR';
        $hash .= strtoupper(md5($parameterBag->get('payhere_merchant_secret')));
        $hash = strtoupper(md5($hash));

        return $this->render('admin_dashboard/transaction/payment_details.html.twig', [
            'transaction' => $transaction,
            'hash' => $hash,
            'payhere_merchant_id' => $parameterBag->get('payhere_merchant_id')
        ]);
    }


    #[Route('/purchase/notify-url', name: 'transaction_payment_notify_url', methods: ['POST'])]
    public function notifyUrlAction(
        Request                $request,
        ManagerRegistry        $doctrine,
        ParameterBagInterface  $parameterBag,
        EntityManagerInterface $em,
        EmailServiceProvider   $mail
    )
    {
        $merchant_id = $request->get('merchant_id');
        $order_id = $request->get('order_id');
        $payhere_amount = $request->get('payhere_amount');
        $payhere_currency = $request->get('payhere_currency');
        $status_code = $request->get('status_code');
        $md5sig = $request->get('md5sig');
        $identification = $request->get('custom_1');
        $payment_id = $request->get('payment_id');
        $method = $request->get('method');

        $tr = $em->getRepository(Transaction::class)->find($order_id);
        if ($order_id) {
            if (isset($tr)) {
                if ($tr->getIdentification() === $identification) {
                    $notifyParametersArray = array(
                        'merchant_id' => $merchant_id,
                        'order_id' => $order_id,
                        'payhere_amount' => $payhere_amount,
                        'payhere_currency' => $payhere_currency,
                        'status_code' => $status_code,
                        'md5sig' => $md5sig,
                        'payment_id' => $payment_id,
                    );

                    //set notifyParameters
                    $tr->setNotifyParameters(json_encode($notifyParametersArray));
                    $em->persist($tr);
                    $em->flush();
                    if (trim($tr->getAmount()) == trim($payhere_amount)) {
                        $merchant_secret = $parameterBag->get('payhere_merchant_secret');
                        $local_md5sig = strtoupper(
                            md5(
                                $merchant_id .
                                $order_id .
                                $payhere_amount .
                                $payhere_currency .
                                $status_code .
                                strtoupper(md5($merchant_secret))
                            )
                        );
                        if (($local_md5sig === $md5sig) && ($status_code == 2)) {
                            // update payment record
                            $tr->setStatus(true)
                                ->setStatusCode($status_code)
                                ->setIdentification('A' . hash('sha512', self::transactionIdentificationCodePrefix . (new \DateTime())->format('YmdHis')))
                                ->setReferenceNo($payment_id)
                                ->setPaidAt(new \DateTimeImmutable());
                            $em->persist($tr);
                            $em->flush();
                            //mail to client
                            $sutemplatePath = 'admin_dashboard/transaction/email/payment_received_mail.html.twig';
                            $tr = $em->getRepository(Transaction::class)->find($tr->getId());
                            $paidDate = null;
                            if ($tr->getPaidAt()) {
                                $paidDate = $tr->getPaidAt()->format('Y-m-d');
                            }
                            $data = [
                                'appointment_ref_no' => $tr->getReferenceNo(),
                                'paid_date' => $paidDate,
                                'first_name' => $tr->getAppointmentId()->getPatientId()->getFirstName(),
                                'last_name' => $tr->getAppointmentId()->getPatientId()->getLastName(),
                                'amount' => $tr->getAppointmentId()->getAmount(),
                                'created_date' => $tr->getAppointmentId()->getCreatedAt()->format('Y-m-d'),
                            ];
                            $clientMail = $tr->getAppointmentId()->getPatientId()->getEmail();
                            $mail->sendPaymentReceivedMail('Payment received', $clientMail, $data, $sutemplatePath);
                            return new Response('success', 200);
                        } else {
                            return new Response('signature mismatch', 400);
                        }
                    } else {
                        return new Response('payment amount mismatch', 400);
                    }
                } else {
                    return new Response('invalid identification', 400);
                }
            } else {
                return new Response('order not available', 400);
            }
        } else {
            return new Response('order id not available', 400);
        }
    }

    #[Route('/purchase/return-url', name: 'transaction_payment_return_url')]
    public function returnUrlAction(Request $request, EntityManagerInterface $em, EmailServiceProvider $mail, MailerInterface $mailer)
    {

        $id = $request->get('order_id');
        $tr = $em->getRepository(Transaction::class)->find($id);

        /** @var Appointment $appointment */
        $processStatus = $em->getRepository(ProcessStatus::class)->findOneBy([
            'metaCode' => 'PAYMENT_DONE'
        ]);
        $appointment = $tr->getAppointmentId();
        $appointment->setPaymentStatus($processStatus);
        $em->persist($appointment);
        $em->flush();


        $sutemplatePath = 'admin_dashboard/transaction/email/payment_received_mail.html.twig';
        $data = [
            'appointment_ref_no' => $tr->getReferenceNo(),
            'paid_date' => '2023-03-24',
            'first_name' => $tr->getAppointmentId()->getPatientId()->getFirstName(),
            'last_name' => $tr->getAppointmentId()->getPatientId()->getLastName(),
            'amount' => $tr->getAppointmentId()->getAmount(),
            'created_date' => $tr->getAppointmentId()->getCreatedAt()->format('Y-m-d'),
        ];
        $clientMail = $tr->getAppointmentId()->getPatientId()->getEmail();
        $mail->sendPaymentReceivedMail('Payment received', $clientMail, $data, $sutemplatePath);


        return $this->render('admin_dashboard/transaction/payment_success.html.twig', [
            'transaction' => $tr
        ]);
    }


    #[Route('/purchase/cancel-url', name: 'transaction_payment_cancel_url')]
    public function cancelUrlAction(
        Request                $request,
        EntityManagerInterface $em,
        EmailServiceProvider   $mail
    )
    {
        $orderId = $request->get('order_id');

        $tr = $em->getRepository(Transaction::class)->find(intval($orderId));
        $tr->setReturnType(0);
        $em->flush();

        $sutemplatePath = 'admin_dashboard/transaction/email/payment_failed.html.twig';
        $paidDate = null;
        if ($tr->getPaidAt()) {
            $paidDate = $tr->getPaidAt()->format('Y-m-d');
        }
        $data = [
            'appointment_ref_no' => $tr->getId(),
            'paid_date' => $paidDate,
            'first_name' => $tr->getAppointmentId()->getPatientId()->getFirstName(),
            'last_name' => $tr->getAppointmentId()->getPatientId()->getLastName(),
            'amount' => $tr->getAppointmentId()->getAmount(),
            'created_date' => $tr->getAppointmentId()->getCreatedAt()->format('Y-m-d'),
        ];
        $clientMail = $tr->getAppointmentId()->getPatientId()->getEmail();
        $mail->sendPaymentReceivedMail('Payment Failed', $clientMail, $data, $sutemplatePath);

        return $this->render('admin_dashboard/transaction/payment_fail.html.twig', [
            'transaction' => $tr
        ]);
    }

    #[Route('/payment_select/{id}', name: 'transaction_payment_select')]
    public function paymentSelectAction($id)
    {

        return $this->render('admin_dashboard/transaction/gateway_selection.html.twig', [
            'id' => $id,
        ]);
    }


    #[Route('/purchase/cancel/payment/{orderId}', name: 'transaction_payment_cancel_by_client')]
    public function cancelPaymentByClientAction(
        Request                $request,
        EntityManagerInterface $em,
        EmailServiceProvider   $mail,
                               $orderId
    )
    {
        $tr = $em->getRepository(Transaction::class)->find(intval($orderId));
        $tr->setReturnType(0);
        $em->flush();

        //send sendPaymentFailureEmail
        $sutemplatePath = 'admin_dashboard/transaction/email/payment_failed.html.twig';
        $paidDate = null;
        if ($tr->getPaidAt()) {
            $paidDate = $tr->getPaidAt()->format('Y-m-d');
        }
        $data = [
            'appointment_ref_no' => $tr->getAppointmentId()->getRefNo(),
            'paid_date' => $paidDate,
            'first_name' => $tr->getAppointmentId()->getPatientId()->getFirstName(),
            'last_name' => $tr->getAppointmentId()->getPatientId()->getLastName(),
            'amount' => $tr->getAppointmentId()->getAmount(),
            'created_date' => $tr->getAppointmentId()->getCreatedAt()->format('Y-m-d'),
        ];
        $clientMail = $tr->getAppointmentId()->getPatientId()->getEmail();
        $mail->sendPaymentReceivedMail('Payment Canceled', $clientMail, $data, $sutemplatePath);
        return $this->render('admin_dashboard/transaction/payment_cancel_client.html.twig', [
            'transaction' => $tr
        ]);
    }
}
