<?php

namespace App\Controller\Tests;

use App\Entity\Test\TestCategory;
use App\Service\Tests\TestCategoryService;
use App\Service\Utils\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/dashboard/test/category')]
class TestCategoryController extends AbstractController
{

    #[Route('/list', name: 'test_category')]
    #[IsGranted('ROLE_VIEW_ADD_EDIT_TEST_CATEGORY')]
    public function getTestCategoryAction(EntityManagerInterface $em): Response
    {
        $testCategories = $em->getRepository(TestCategory::class)->findBy([], ['name' => 'ASC']);
        return $this->render('admin_dashboard/tests/category/category_list.html.twig', [
            'categories' => $testCategories
        ]);
    }


    #[Route('/ajax/testCategory/form', name: 'ajax_test_category_form')]
    #[IsGranted('ROLE_VIEW_ADD_EDIT_TEST_CATEGORY')]
    public function ajaxTestCategoryFormAction(Request             $request,
                                               ApiResponse         $apiResponse,
                                               TestCategoryService $testCategoryService
    ): Response
    {
        $res = ['status' => false, 'message' => null, 'payload' => []];
        $error_code = 0;
        try {
            if ($request->isMethod('POST')) {
                $res = $testCategoryService->saveTestCategoryFormData($request);
            } else {
                $res = $testCategoryService->getTestCategoryFormData($request);
            }
        } catch (\Exception $exception) {
            $res['message'] = $exception->getMessage();
            $error_code = $exception->getCode();
        }

        $resObj = $apiResponse->getResponseObj($res['status'], $res['message'], $res['payload'], $error_code);
        return $apiResponse->apiSendResponse($resObj);
    }

}