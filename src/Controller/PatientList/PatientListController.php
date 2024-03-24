<?php

namespace App\Controller\PatientList;

use App\Entity\User\AppUser;
use App\Utils\GenerateExcelSheet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('admin/dashboard/patient')]
class PatientListController extends AbstractController
{

    #[Route('/list', name: 'patient_list_view')]
    #[IsGranted('ROLE_VIEW_PATIENTS_LIST')]
    public function patientListAction(EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(AppUser::class)->findBy([
            'isDeleted' => false,
            'isBackendUser' => false,
        ]);
        return $this->render('admin_dashboard/patient_list/patient_list.html.twig', [
            'users' => $user
        ]);
    }

    #[Route('/ajax/save', name: 'ajax_save_patients_report')]
    public function saveFileAction(GenerateExcelSheet $excelSheet, Request $request,EntityManagerInterface $em)
    {

        $columnsHeaders = [
            'FIRST NAME',
            'LAST NAME',
            'USER ID',
            'E-MAIL',
            'CONTACT NO',
            'GENDER',
            'DOB',
            'CREATED AT'

        ];
        $dataSet = [];
        $patients = $em->getRepository(AppUser::class)->findBy([
            'isDeleted' => false,
            'isBackendUser' => false,
        ]);

        $title = 'PATIENTS LIST REPORT';
        foreach ($patients as $patient)
        {
            $dataSet[] = [
                $patient->getFirstName(),
                $patient->getLastName(),
                $patient->getUserId(),
                $patient->getEmail(),
                $patient->getContactNumber(),
                $patient->getGender(),
                $patient->getDob()->format('Y-m-d'),
                $patient->getCreatedAt()->format('Y-m-d'),
            ];
        }


        $sheetData = [
            'title' => 'PATIENTS LIST',
            'columnsHeaders' => $columnsHeaders,
            'data' => $dataSet,
            'height' => 25,
            'alignLeft' => ['A','B','C','D','E','F','G','H'],
            'alignRight' => [],
            'alignCenter' => [],
            'currencyColumn' => [],
        ];

        $excelSheet = $excelSheet->getFormattedExcelSheet($sheetData);
        if ($excelSheet) {
            return $this->redirectToRoute('download_patient_list_report');
        } else {
            return new JsonResponse('error');
        }
    }


    #[Route('/download', name: 'download_patient_list_report')]
    public function downloadFileAction()
    {

        $filename = 'PATIENTS LIST REPORT.xlsx';

        $basePath = $this->getParameter('kernel.project_dir');
        $reportsFolderPath = $basePath . '/reports/backend_reports';
        $filePath = $reportsFolderPath . '/' . $filename;


        $fs = new Filesystem();
        if (!$fs->exists($filePath)) {
            throw $this->createNotFoundException();
        }

        // prepare BinaryFileResponse
        $response = new BinaryFileResponse($filePath);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );

        return $response;

    }

}