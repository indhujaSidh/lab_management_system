<?php

namespace App\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('',name:'admin_dashboard')]
    #[IsGranted('ROLE_VIEW_DASHBOARD')]
    public function dashboardAction():Response
    {
        return $this->render('admin_dashboard/dashboard/main_page.html.twig');
    }

}