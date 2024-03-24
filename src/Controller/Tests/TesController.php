<?php

namespace App\Controller\Tests;

use App\Entity\Test\Test;
use App\Form\Type\Test\TestType;
use App\Service\Tests\TestService;
use App\Service\Utils\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/dashboard/test')]
class TesController extends AbstractController
{
    #[Route('/list', name: 'dashboard_tests_list')]
    #[IsGranted('ROLE_VIEW_ADD_EDIT_TEST_INFO')]
    public function testControllerAction(): Response
    {
        return $this->render('admin_dashboard/tests/tests/test_list.html.twig');
    }

    #[Route('/ajax/getTestsResults', name: 'ajax_get_tests_result')]
    #[IsGranted('ROLE_VIEW_ADD_EDIT_TEST_INFO')]
    public function ajaxGetTestsListAction(ApiResponse $apiResponse, TestService $testService): Response
    {
        $res = ['status' => false, 'message' => null, 'payload' => []];
        $error_code = 0;
        try {
            $dataSet = $testService->getTestsForListView();
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


    #[Route('/addNew', name: 'add_new_test')]
    public function addNewTestAction(Request $request,EntityManagerInterface $em): Response
    {
        $test = new Test();
        $form = $this->createForm(TestType::class, $test);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $test = $form->getData();
            $em->persist($test);
            $em->flush();
            $this->addFlash('success', 'Saved successfully');
            return $this->redirectToRoute('dashboard_tests_list');
        }

        return $this->render('admin_dashboard/tests/tests/tests_form.html.twig', [
            'isNew' => true,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/edit', name: 'edit_test_info')]
    public function editTestResults(Request $request,EntityManagerInterface $em):Response
    {
        $id = $request->get('id');
        $test = $em->getRepository(Test::class)->find($id);
        $form = $this->createForm(TestType::class, $test);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $test = $form->getData();
            $em->persist($test);
            $em->flush();
            $this->addFlash('success', 'Updated successfully');
            return $this->redirectToRoute('dashboard_tests_list');
        }

        return $this->render('admin_dashboard/tests/tests/tests_form.html.twig', [
            'isNew' => false,
            'form' => $form->createView(),
        ]);
    }



    #[Route('/ajax/isActive', name: 'ajax_change_test_isActive_status')]
    public function changeTestIsActiveStatus(Request $request, EntityManagerInterface $em):Response
    {
        $id = $request->get('id');
        $isActive = $request->get('isActive');
        $test = $em->getRepository(Test::class)->find($id);
        $test->setIsActive($isActive);
        $em->persist($test);
        $em->flush();

        return new JsonResponse('success');
    }


    #[Route('/ajax/isADeleted', name: 'ajax_delete_test_records')]
    public function changeDeleteAction(Request $request,EntityManagerInterface $em):Response
    {
        $id = $request->get('id');
        $test = $em->getRepository(Test::class)->find($id);
        $test->setIsDeleted(true);
        $em->persist($test);
        $em->flush();

        return new JsonResponse('success');

    }

}