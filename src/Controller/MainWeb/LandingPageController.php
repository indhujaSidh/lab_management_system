<?php

namespace App\Controller\MainWeb;

use App\Entity\Appointment\PreRequests;
use App\Entity\ProcessStatus;
use App\Form\Type\web\PreRequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('')]
class LandingPageController extends AbstractController
{

    #[Route('', name: 'home_page')]
    public function landingPageAction(Request $request, SluggerInterface $slugger,EntityManagerInterface $em): Response
    {
        $preRequests = new PreRequests();
        $fomAppointment = $this->createForm(PreRequestType::class, $preRequests);
        $fomAppointment->handleRequest($request);
        if ($fomAppointment->isSubmitted()) {
            /** @var UploadedFile $testRequisitionFile */
            $testRequisitionFile = $fomAppointment->get('testRequisitionFilename')->getData();
            $preRequests = $fomAppointment->getData();
            if ($testRequisitionFile) {
                $originalFilename = pathinfo($testRequisitionFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $testRequisitionFile->guessExtension();
                try {
                    $testRequisitionFile->move(
                        $this->getParameter('test_requisition_file_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $processState = $em->getRepository(ProcessStatus::class)->findOneBy([
                    'metaCode' => 'APPOINTMENT_REQUEST_RECEIVED'
                ]);
                $preRequests->setProcessState($processState);
                $preRequests->setTestRequisitionFilename($newFilename);
                $em->persist($preRequests);
                $em->flush();

                $this->addFlash('success','Received');

                return $this->redirectToRoute('home_page');

            }

        }
        return $this->render('main_web/landing_page/landing_page.html.twig', [
            'formAppointment' => $fomAppointment->createView()
        ]);
    }

}