<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Controller;

use App\Service\NewsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route(name: 'app_news_')]
class NewsController extends AbstractController {
    private NewsService $newsService;

    public function __construct(NewsService $newsService) {
        $this->newsService = $newsService;
    }

    #[Route(['/News', '/news'], name: 'list')]
    public function list(): Response {

        return $this->render('News/List.twig', [
            'News' => $this->newsService->getNewsByLang(),
        ]);
    }
}
