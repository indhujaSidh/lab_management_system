<?php

namespace App\Controller\Appointment;

use App\Entity\Appointment\Appointment;
use App\Entity\Appointment\AppointmentTestMappings;
use App\Entity\ProcessStatus;
use App\Entity\Test\Test;
use App\Entity\TimeSlot\TimeSlot;
use App\Form\Model\AppointmentModel;
use App\Form\Type\Appointment\AppointmentType;
use App\Service\Appointment\AppointmentService;
use App\Service\Appointment\IAppointmentService;
use App\Service\User\IAppUserService;
use App\Service\Utils\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/dashboard')]
class AppointmentController extends AbstractController
{

    #[Route('/place/appointment', name: 'admin_make_appointment')]
    #[IsGranted('ROLE_PLACE_APPOINTMENT')]
    public function makeAppointmentAction(Request $request, EntityManagerInterface $em): Response
    {
        $appointmentModel = new AppointmentModel();
        $form = $this->createForm(AppointmentType::class, $appointmentModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData()->toArray();
            //appointment
            $appointment = new Appointment();
            $appointment->setPatientId($data['patient']);
            if ($data['doctor']) {
                $appointment->setDoctorId($data['doctor']);
            }
            $status = $em->getRepository(ProcessStatus::class)->findOneBy([
                'metaCode' => $data['paymentStatus']
            ]);
            $appointment->setPaymentStatus($status);
            $appointment->setTimeSlot($data['timeSlot']);
            if ($data['refDoctor']) {
                $appointment->setRefDoctor($data['refDoctor']);
            }
            $timeZone = new \DateTimeZone('Asia/Colombo');
            $today = new \DateTime();
            $todayString = $today->format('Y-m-d');
            $appointment->setCreatedAt(new \DateTimeImmutable('now', $timeZone));
            $appointment->setUpdatedAt(new \DateTimeImmutable('now', $timeZone));

            $randomId = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4);
            $patient = $data['patient']->getId();
            $ref = 'AUTOMIC-NO-' . $todayString . '-PATIENT-' . $patient . '-' . $randomId;
            $appointment->setRefNo($ref);
            $appointment->setAmount(0);
            $em->persist($appointment);
            $em->flush();
            $amount = 0;

            if (!empty($data['tests'])) {
                foreach ($data['tests'] as $test) {
                    $testInfo = $test->toArray();
                    /** @var Test $te */
                    $te = $testInfo['name'];

                    //appointment Test Mapping
                    $appointmentMapping = new AppointmentTestMappings();
                    $appointmentMapping->setAppointmentId($appointment);
                    $appointmentMapping->setSampleCollected($data['sampleCollected']);
                    $appointmentMapping->setTest($te);
                    $em->persist($appointmentMapping);
                    $em->flush();
                    $amount = $amount + $te->getPrice();
                }
            }
            $appointment->setAmount($amount);
            $em->persist($appointment);
            $em->flush();

            //update timeslot
            if ($data['timeSlot']) {
                /** @var TimeSlot $timeSlot */
                $timeSlot = $data['timeSlot'];
                $timeSlot->setAvailableSlots($timeSlot->getAvailableSlots() - 1);
                $em->persist($timeSlot);
                $em->flush();

            }

            $this->addFlash('success', 'Appointment created successfully');
            return $this->redirectToRoute('admin_make_appointment');
        }

        return $this->render('admin_dashboard/appointment/appointment_form.html.twig', [
            'formAppointment' => $form->createView(),
            'isNew' => true
        ]);


    }

    #[Route('/ajax/auto/complete/user/contacts', name: 'ajax_auto_complete_user_contacts')]
    #[IsGranted('ROLE_PLACE_APPOINTMENT')]
    public function autoCompleteUserContacts(Request $request, IAppUserService $appUserService): Response
    {
        $input = $request->query->get('input');
        $limit = $request->query->getInt('limit', 20);

        $primaryContacts = $appUserService->getUsersContactNumbers([
            'limit' => $limit,
            'input' => $input,
        ]);

        $data = [
            'primary' => $primaryContacts,
        ];

        return new JsonResponse($data);
    }

    #[Route('/appointment/list', name: 'appointment_list')]
    #[IsGranted('ROLE_PLACE_APPOINTMENT')]
    public function appointmentListAction(): Response
    {
        return $this->render('admin_dashboard/appointment/appointment_list.html.twig');

    }


    #[Route('/ajax/appointment/list', name: 'ajax_appointment_list')]
    #[IsGranted('ROLE_PLACE_APPOINTMENT')]
    public function ajaxGetAppointmentList(IAppointmentService $appointmentService,ApiResponse $apiResponse)
    {
        $res = ['status' => false, 'message' => null, 'payload' => []];
        $error_code = 0;
        try {
            $dataSet = $appointmentService->getAppointmentListAction(null);
            $res['status'] = true;
            $res['payload'] = [
                'dataSet' => $dataSet,
            ];
        } catch (\Exception $exception) {
            $res['message'] = $exception->getMessage();
            $error_code = $exception->getCode();
        }

        $resObj = $apiResponse->getResponseObj($res['status'], $res['message'], $res['payload'], $error_code);
        return $apiResponse->apiSendResponse($resObj);
    }





}