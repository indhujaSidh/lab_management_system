<?php

namespace App\Service\Tests;

use App\Entity\Test\TestCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TestCategoryService implements ITestCategoryService
{

    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }


    /**
     * @param Request $request
     * @return array
     */
    public function saveTestCategoryFormData(Request $request): array
    {
        $res = ['status' => false, 'message' => null, 'payload' => []];
        $error_code = 0;
        try {
            $testCategory = new TestCategory();
            $id = $request->get('id');
            if ($id && is_numeric($id)) {
                $testCategory = $this->em->getRepository(TestCategory::class)->find($id);
            }
            $name = $request->get('name');
            if (!$name) {
                $res['message'] = 'Name is required';
                return $res;
            }
            $testCategory->setName($name);
            $code = str_replace(' ', '_', trim($name));
            $testCategory->setMetaCode($code);
            $this->em->persist($testCategory);
            $this->em->flush();
            $res['status'] = true;
            $res['message'] = 'Saved successfully !';
        } catch (\Exception $exception) {
            $res['message'] = $exception->getMessage();
            $error_code = $exception->getCode();
        }
        return $res;
    }


    /**
     * @param Request $request
     * @return array
     */
    public function getTestCategoryFormData(Request $request): array
    {
        $res = ['status' => false, 'message' => null, 'payload' => []];
        $error_code = 0;
        try {
            $id = $request->get('id');
            $name = '';
            if ($id && is_numeric($id)) {
                $testCategory = $this->em->getRepository(TestCategory::class)->find($id);

                if (!empty($testCategory)) {
                    $name = $testCategory->getName();
                } else {
                    $res['message'] = 'Invalid request';
                    return $res;
                }
            }
            $dataSet = [
                'name' => $name
            ];
            $res['status'] = true;
            $res['payload'] = [
                'dataSet' => $dataSet,
            ];
        } catch (\Exception $exception) {
            $res['message'] = $exception->getMessage();
            $error_code = $exception->getCode();
        }
        return $res;
    }


}