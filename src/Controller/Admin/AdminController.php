<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')] 
#[Route(['/Admin', '/admin'], name: 'admin_dashboard_')]
class AdminController extends AbstractController {

    #[IsGranted('ROLE_ADMIN', message: 'You are not allowed to access the admin dashboard.')] 
    #[Route(['/Index', '/index'], name: 'index')]
    public function index(): Response {

        // Rend la vue 
        return $this->render('Admin/Index.twig', []);
    }
}
