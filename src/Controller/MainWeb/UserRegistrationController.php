<?php

namespace App\Controller\MainWeb;

use App\Entity\User\AppUser;
use App\Entity\User\UserRole;
use App\Form\Type\web\userRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/user/registration')]
class UserRegistrationController extends AbstractController
{

    #[Route('',name: 'user_registration')]
    public function userRegistration(Request $request,UserPasswordHasherInterface $passwordHasher,EntityManagerInterface $em):Response
    {
        $user = new AppUser();
        $form = $this->createForm(userRegistrationType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $role = $em->getRepository(UserRole::class)->findOneBy([
                'metaCode' => 'PATIENT'
            ]);
            $bUser = $form->getData();
            $password = $bUser->getPassword();
            $encoded = $passwordHasher->hashPassword($bUser, $password);
            $bUser->setPassword($encoded);
            $timeZone = new \DateTimeZone('Asia/Colombo');
            $bUser->setCreatedAt(new \DateTimeImmutable('now', $timeZone));
            $bUser->setIsBackendUser(false);
            $bUser->setRole($role);
            $em->persist($bUser);
            $em->flush();
            $userId = $bUser->getId();
            $randomId = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 5);
            $userId = 'AUT-PAT-'.$userId.'-'. $randomId;
            $bUser->setUserId($userId);
            $em->persist($bUser);
            $em->flush();

            $this->addFlash('success', 'Created Successfully');
            return $this->redirectToRoute('app_login');

        }

        return $this->render('main_web/user_registration/user_registration.html.twig',[
            'formUser' => $form->createView()
        ]);
    }

}