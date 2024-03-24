<?php

namespace App\Controller\PatientProfile;

use App\Entity\User\AppUser;
use App\Form\Type\web\userRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/patient/profile/edit')]
class PatientProfileController extends AbstractController
{
    #[Route('', name: 'edit_patient_profile')]
    #[IsGranted('ROLE_VIEW_AND_EDIT_PATIENT_PROFILE')]
    public function editPatientProfileAction(EntityManagerInterface $em,Request $request,UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        if ($user) {
            $patient = $em->getRepository(AppUser::class)->find($user);
            $form = $this->createForm(userRegistrationType::class, $patient);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $patient = $form->getData();
                $password = $patient->getPassword();
                $encoded = $passwordHasher->hashPassword($patient, $password);
                $patient->setPassword($encoded);
                $em->persist($patient);
                $em->flush();

                $this->addFlash('success', 'Updated Successfully');
                return $this->redirectToRoute('edit_patient_profile');

            }
            return $this->render('admin_dashboard/patient/profile/profile_edit.html.twig', [
                'formUser' => $form->createView()
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

}