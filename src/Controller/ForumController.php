<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

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
        return $this->render('Forum/Index.twig', [
            'controller_name' => 'ForumController',
        ]);
    }
}
