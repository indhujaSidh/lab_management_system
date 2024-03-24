<?php

namespace App\Controller\TestView;

use App\Entity\Appointment\Appointment;
use App\Entity\Appointment\AppointmentTestMappings;
use App\Form\Type\Appointment\AppointmentTestMappingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/dashboard/test')]
class TestViewController extends AbstractController
{

    #[Route('/results/overView', name: 'test_results_overView')]
    #[IsGranted('ROLE_VIEW_AND_UPLOAD_TEST_RESULTS')]
    public function getTestResultsListAction()
    {
        return $this->render('admin_dashboard/dashboard_tests_view/test_view.html.twig');

    }

    #[Route('/upload/report', name: 'upload_test_report')]
    #[IsGranted('ROLE_VIEW_AND_UPLOAD_TEST_RESULTS')]
    public function uploadTestReportAction(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $id = $request->get('id');
        $appointmentTestMapping = $em->getRepository(AppointmentTestMappings::class)->find($id);
        $appointment = $appointmentTestMapping->getAppointmentId();

        $form = $this->createForm(AppointmentTestMappingType::class, $appointmentTestMapping);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $appointmentTestMapping = $form->getData();
            /** @var UploadedFile $reportFile */
            $reportFile = $form->get('reportFile')->getData();
            if ($reportFile) {
                $originalFilename = pathinfo($reportFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $appointment->getRefNo() . '-' . $safeFilename . '-' . uniqid() . '.' . $reportFile->guessExtension();
                try {
                    $reportFile->move(
                        $this->getParameter('test_report_upload_file_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $appointmentTestMapping->setReportFile($newFilename);
            }

            $em->persist($appointmentTestMapping);
            $em->flush();
            $this->addFlash('success', 'Updated Successfully');
            return $this->redirectToRoute('test_results_overView');
        }

        return $this->render('admin_dashboard/dashboard_tests_view/upload_test.html.twig', [
            'form' => $form->createView(),
            'appointment_ref' => $appointment->getRefNo(),
        ]);
    }


    #[Route('/report/download',name:"download_patient_report_file")]
    public function downloadFile(Request $request)
    {

        $fileName = $request->get('fileName');
        $filePath =  $this->getParameter('test_report_upload_file_directory').'/'.$fileName;
        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('The file does not exist');
        }

        // Create a BinaryFileResponse object
        $response = new BinaryFileResponse($filePath);

        // Set the filename to be downloaded
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileName);

        return $response;
    }

}