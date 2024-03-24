<?php

namespace App\Controller\PatientProfile;


use App\Entity\Appointment\Appointment;
use App\Service\Appointment\AppointmentMappingService;
use App\Service\Utils\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/appointment/test/results')]
class AppointmentTestResultController extends AbstractController
{

    #[Route('',name:'patient_test_results')]
    #[IsGranted('ROLE_VIEW_APPOINTMENTS')]
    public function appointmentTestResultsAction(Request $request,EntityManagerInterface $em):Response
    {
        $appointmentId = $request->get('appointmentId');
        $appointment = $em->getRepository(Appointment::class)->find($appointmentId);
        return $this->render('admin_dashboard/patient/test_results/test_results.html.twig',[
            'appointmentId' => $appointmentId,
            'appointmentRef' => $appointment->getRefNo(),
        ]);

    }


    #[Route('/ajax/get/testResults',name:'ajax_get_test_results')]
    public function ajaxGetPatientTestResultsAction(ApiResponse $apiResponse,Request $request,AppointmentMappingService $appointmentMappingService):Response
    {
        $appointmentId = $request->get('appointmentId');
        $res = ['status' => false, 'message' => null, 'payload' => []];
        $error_code = 0;
        try {
            $dataSet = $appointmentMappingService->getAppointmentTestResults($appointmentId);
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