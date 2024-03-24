<?php

namespace App\Controller\User;

use App\Entity\User\AppUser;
use App\Form\Model\PasswordResetModal;
use App\Form\Type\User\BackendUserType;
use App\Form\Type\User\PasswordResetType;
use App\Service\User\AppUserService;
use App\Service\Utils\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/admin/dashboard/backendUsers')]
class BackendUserController extends AbstractController
{

    #[Route('/list', name: 'backend_users_list')]
    #[IsGranted('ROLE_VIEW_ADD_EDIT_BACKEND_USERS')]
    public function getBackendUsersListAction(AppUserService $appUserService): Response
    {
        $user = $appUserService->getBackendUserListAction();
        return $this->render('admin_dashboard/user/backend_user/backend_user_list.html.twig', [
            'users' => $user
        ]);
    }

    #[Route('/addNew', name: 'add_new_backend_user')]
    #[IsGranted('ROLE_VIEW_ADD_EDIT_BACKEND_USERS')]
    public function addNewBackendUserAction(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $bUser = new AppUser();
        $form = $this->createForm(BackendUserType::class, $bUser, ['isNew' => true]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $bUser = $form->getData();
            $password = $bUser->getPassword();
            $encoded = $passwordHasher->hashPassword($bUser, $password);
            $bUser->setPassword($encoded);
            $timeZone = new \DateTimeZone('Asia/Colombo');
            $bUser->setCreatedAt(new \DateTimeImmutable('now', $timeZone));
            $bUser->setIsBackendUser(true);
            $randomId = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 5);
            $userId = 'AUTOMIC' . $randomId;
            $bUser->setUserId($userId);
            $em->persist($bUser);
            $em->flush();

            $this->addFlash('success', 'Created Successfully');
            return $this->redirectToRoute('backend_users_list');
        }

        return $this->render('admin_dashboard/user/backend_user/backend_user_form.html.twig', [
            'formUser' => $form->createView(),
            'isNew' => true,
        ]);

    }


    #[Route('/edit', name: 'edit_backend_user')]
    #[IsGranted('ROLE_VIEW_ADD_EDIT_BACKEND_USERS')]
    public function editBackendUserDetails(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $id = $request->get('id');
        $bUser = $em->getRepository(AppUser::class)->find($id);
        $isNew = false;
        $form = $this->createForm(BackendUserType::class, $bUser, ['isNew' => $isNew]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $bUser = $form->getData();
            $em->persist($bUser);
            $em->flush();
            $this->addFlash('success', 'Updated Successfully');
            return $this->redirectToRoute('backend_users_list');
        }

        $passReset = new PasswordResetModal();
        $formResetPassword = $this->createForm(PasswordResetType::class, $passReset);

        return $this->render('admin_dashboard/user/backend_user/backend_user_form.html.twig', [
            'formUser' => $form->createView(),
            'isNew' => false,
            'formResetPassword' => $formResetPassword->createView(),
            'userId' => $id,
        ]);

    }


    #[Route('/ajax/change/password', name: 'ajax_reset_backend_user_password')]
    #[IsGranted('ROLE_VIEW_ADD_EDIT_BACKEND_USERS')]
    public function changeBackendUserPassword(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $res = ['status' => false, 'message' => null, 'sameUser' => false];
        $id = $request->get('id');
        $old = $request->get('old');
        $pass = $request->get('pass');

        $user = $em->getRepository(AppUser::class)->find($id);
        if ($user) {
            if (!($passwordHasher->isPasswordValid($user, $old))) {
                $res['message'] = 'Old Password is incorrect';
            } else {
                $encoded = $passwordHasher->hashPassword($user, $pass);
                $user->setPassword($encoded);
                $em->persist($user);
                $em->flush();
                $res['message'] = 'Password changed successfully';
                $res['status'] = true;
            }
        } else {
            $res['message'] = 'User not found! ';
        }

        return new JsonResponse($res);
    }


    #[Route('/isActive', name: 'ajax_change_backend_user_active_status')]
    #[IsGranted('ROLE_VIEW_ADD_EDIT_BACKEND_USERS')]
    public function changeIsActiveStatus(Request $request, EntityManagerInterface $em): Response
    {
        $id = $request->get('id');
        $isActive = $request->get('isActive');
        $doctor = $em->getRepository(AppUser::class)->find($id);
        $doctor->setIsActive($isActive);
        $em->persist($doctor);
        $em->flush();

        return new JsonResponse('success');

    }
}