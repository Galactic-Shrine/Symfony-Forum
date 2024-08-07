<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response; 
Use Symfony\Component\Routing\Attribute\Route;

#[Route(name: 'app_forum_')]
class ForumController extends AbstractController
{
    #[Route(['/Forum', '/forum'], name: 'index')]
    public function index(): Response
    {
        return $this->render('forum/index.twig', [
            'controller_name' => 'ForumController',
        ]);
    }
}
