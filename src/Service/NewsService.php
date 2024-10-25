<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Service;

use Symfony\Component\Uid\Uuid;
use App\Repository\NewsRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class NewsService {

    private ?string $LocaleVars;
    private NewsRepository $NewsRepository;

    public function __construct(NewsRepository $NewsRepository, RequestStack $requestStack) {
        
        // Récupère la requête courante et la locale active
        $currentRequest = $requestStack->getCurrentRequest();
        if ($currentRequest) {
            $this->LocaleVars = $currentRequest->getLocale(); // ex: 'en', 'fr'
        } else {
            // Si pas de requête active, utilise une valeur par défaut
            $this->LocaleVars = 'en'; // Valeur par défaut si aucune locale trouvée
        }
        $this->NewsRepository = $NewsRepository;
    }

    public function getListNewsByLang(): array {
        
        $NewsList = $this->NewsRepository->findAll();
        $FilteredNews = [];

        foreach ($NewsList as $News) {

            $Title = $News->getTitleByLang($this->LocaleVars);
            $Contents = $News->getContentsByLang($this->LocaleVars);
            $ContentsContinued = $News->getContentsContinuedByLang($this->LocaleVars);

            if ($Title !== null && $Contents !== null) {
                
                $FilteredNews[] = [
                    'Id' => $News->getId()->toRfc4122(), // Ajout de l'ID
                    'Title' => $Title,
                    'Contents' => $Contents,
                    'Slug' => $News->getSlug(),
                    'CreatedAt' => $News->getCreatedAt(),
                ];
            }
        }

        return $FilteredNews;
    }

    // Récupérer une seule news par ID
    public function getNewsById(Uuid $id): ?array {
        $news = $this->NewsRepository->find($id);

        if (!$news) {
            return null; // ou vous pouvez lancer une exception
        }

        // Récupère les données en fonction de la langue courante
        $title = $news->getTitleByLang($this->LocaleVars);
        $contents = $news->getContentsByLang($this->LocaleVars);
        $contentsContinued = $news->getContentsContinuedByLang($this->LocaleVars);

        return [
            'Id' => $news->getId()->toRfc4122(),
            'Title' => $title,
            'Contents' => $contents,
            'ContentsContinued' => $contentsContinued,
            'Slug' => $news->getSlug(),
            'CreatedAt' => $news->getCreatedAt(),
        ];
    }
}
