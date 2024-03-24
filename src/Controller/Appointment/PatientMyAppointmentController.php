<?php

namespace App\Controller\Appointment;

use App\Service\Appointment\AppointmentService;
use App\Service\UrlSignerService;
use App\Service\Utils\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/myAppointments/list')]
class PatientMyAppointmentController extends AbstractController
{


    #[Route('', name: 'my_appointments_list')]
    #[IsGranted('ROLE_VIEW_PATIENTS_APPOINTMENTS')]
    public function myAppointmentsListAction(): Response
    {
        $user = $this->getUser();
        if (!empty($user)) {
            return $this->render('admin_dashboard/patient/appointment/my_appointments.html.twig');
        } else {
            return $this->redirectToRoute('app_login');
        }

    }

    #[Route('/ajax/appointment/list', name: 'ajax_my_appointment_list')]
    #[IsGranted('ROLE_VIEW_PATIENTS_APPOINTMENTS')]
    public function ajaxGetAppointmentList(AppointmentService $appointmentService, ApiResponse $apiResponse)
    {
        $res = ['status' => false, 'message' => null, 'payload' => []];
        $error_code = 0;
        $user = $this->getUser();
        if (!empty($user)) {
            try {
                $dataSet = $appointmentService->getAppointmentListAction($user->getId());
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


    #[Route('/redirect/to/payment', name: 'redirect_user_to_payment')]
    #[IsGranted('ROLE_VIEW_PATIENTS_APPOINTMENTS')]
    public function redirectToPaymentGatewayAction(Request $request):Response
    {
        $id = $request->get('id');
        return $this->redirectToRoute('transaction_level_payment_pay', ['id' => $id]);
    }

}