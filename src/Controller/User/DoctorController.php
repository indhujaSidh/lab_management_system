<?php

namespace App\Controller\User;

use App\Entity\User\Doctor;
use App\Form\Type\User\DoctorType;
use App\Service\Utils\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\User\DoctorService;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/dashboard/doctor')]
class DoctorController extends AbstractController
{
    #[Route('/lists', name: 'dashboard_doctors_list')]
    #[IsGranted('ROLE_VIEW_ADD_EDIT_DOCTOR')]
    public function doctorListViewAction(): Response
    {
        return $this->render('admin_dashboard/user/doctor/doctor_list.html.twig');
    }

    #[Route('/ajax/doctorList', name: 'ajax_get_doctor_list')]
    #[IsGranted('ROLE_VIEW_ADD_EDIT_DOCTOR')]
    public function ajaxGetDoctorListAction(DoctorService $doctorService,
                                            ApiResponse   $apiResponse,
                                            Request       $request): Response
    {
        $res = ['status' => false, 'message' => null, 'payload' => []];
        $error_code = 0;
        try {
            $dataSet = $doctorService->getDoctorsForList();
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

    #[Route('/addNew', name: 'add_new_doctor')]
    #[IsGranted('ROLE_VIEW_ADD_EDIT_DOCTOR')]
    public function addNewDoctorAction(Request $request,EntityManagerInterface $em):Response
    {

        $doctor = new Doctor();
        $form = $this->createForm(DoctorType::class,$doctor);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $doctor = $form->getData();
            $em->persist($doctor);
            $em->flush();

            $this->addFlash('success', 'Updated successfully');
            return $this->redirectToRoute('dashboard_doctors_list');
        }

        return $this->render('admin_dashboard/user/doctor/edit_doctor.html.twig', [
            'formDoctor' => $form->createView(),
            'isNew' => true
        ]);

    }


    #[Route('/editInfo', name: 'edit_doctor_info')]
    #[IsGranted('ROLE_VIEW_ADD_EDIT_DOCTOR')]
    public function editDoctorAction(Request $request,EntityManagerInterface $em):Response
    {
        $id = $request->get('id');
        $doctor = $em->getRepository(Doctor::class)->find($id);
        $form = $this->createForm(DoctorType::class,$doctor);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $doctor = $form->getData();
            $em->persist($doctor);
            $em->flush();

            $this->addFlash('success', 'Updated successfully');
            return $this->redirectToRoute('dashboard_doctors_list');
        }

        return $this->render('admin_dashboard/user/doctor/edit_doctor.html.twig', [
            'formDoctor' => $form->createView(),
            'isNew' => false
        ]);

    }


    #[Route('/delete', name: 'ajax_delete_doctor_info')]
    #[IsGranted('ROLE_VIEW_ADD_EDIT_DOCTOR')]
    public function deleteDoctorInfo(Request $request,EntityManagerInterface $em):Response
    {
        $id = $request->get('id');
        $doctor = $em->getRepository(Doctor::class)->find($id);
        $doctor->setIsDeleted(true);
        $em->persist($doctor);
        $em->flush();

        return new JsonResponse('success');

    }


    #[Route('/isActive', name: 'ajax_change_doctor_active_status')]
    #[IsGranted('ROLE_VIEW_ADD_EDIT_DOCTOR')]
    public function changeIsActiveStatus(Request $request,EntityManagerInterface $em):Response
    {
        $id = $request->get('id');
        $isActive = $request->get('isActive');
        $doctor = $em->getRepository(Doctor::class)->find($id);
        $doctor->setIsActive($isActive);
        $em->persist($doctor);
        $em->flush();

        return new JsonResponse('success');

    }

}