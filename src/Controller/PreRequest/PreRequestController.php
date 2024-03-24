<?php

namespace App\Controller\PreRequest;

use App\Entity\Appointment\PreRequests;
use App\Form\Type\PreRequest\PreRequestViewType;
use App\Service\PreRequest\PreRequestService;
use App\Service\Utils\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('admin/dashboard/preRequest')]
class PreRequestController extends AbstractController
{
    #[Route('/list',name:"appointment_requests")]
    #[IsGranted('ROLE_VIEW_APPOINTMENT_REQUEST')]
    public function preRequestListAction():Response
    {
        return $this->render('admin_dashboard/pre_request/pre_request.html.twig');
    }


    #[Route('/ajax/pre_request_list',name:"ajax_get_preRequest_list")]
    #[IsGranted('ROLE_VIEW_APPOINTMENT_REQUEST')]
    public function getPreRequestListAction(ApiResponse $apiResponse,PreRequestService $requestService):Response
    {
        $res = ['status' => false, 'message' => null, 'payload' => []];
        $error_code = 0;
        try {
            $dataSet = $requestService->getPreRequestList();
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


    #[Route('/download',name:"download_pre_request_file")]
    #[IsGranted('ROLE_VIEW_APPOINTMENT_REQUEST')]
    public function downloadFile(Request $request)
    {

        $fileName = $request->get('fileName');
        $filePath =  $this->getParameter('test_requisition_file_directory').'/'.$fileName;
        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('The file does not exist');
        }

        // Create a BinaryFileResponse object
        $response = new BinaryFileResponse($filePath);

        // Set the filename to be downloaded
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName);

        return $response;
    }


    #[Route('/view',name:"view_pre_request")]
    #[IsGranted('ROLE_VIEW_APPOINTMENT_REQUEST')]
    public function editPreRequestAction(Request $request,EntityManagerInterface $em):Response
    {
        $id = $request->get('id');
        $preRequest = $em->getRepository(PreRequests::class)->find($id);
        $form = $this->createForm(PreRequestViewType::class,$preRequest);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $preRequest = $form->getData();
            $em->persist($preRequest);
            $em->flush();
            $this->addFlash('success','Updated Successfully');
            return $this->redirectToRoute('appointment_requests');
        }

        return $this->render('admin_dashboard/pre_request/pre_request_form.html.twig',[
            'form' => $form->createView()
        ]);
    }





}