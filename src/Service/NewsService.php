<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Service;

use App\Repository\NewsRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;

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

    public function getNewsByLang(): array {
  
        $NewsList = $this->NewsRepository->findAll();
        //dump($NewsList); // Affiche les news récupérées depuis la base de données

        $FilteredNews = [];

        foreach ($NewsList as $News) {

            $Title = $News->getTitleByLang("$this->LocaleVars");
            $Contents = $News->getContentsByLang($this->LocaleVars);
            $ContentsContinued = $News->getContentsContinuedByLang($this->LocaleVars);

            if ($Title !== null && $Contents !== null) {

                $FilteredNews[] = [
                    'Id' => $News->getId(), // Ajout de l'ID
                    'Title' => $Title,
                    'Contents' => $Contents,
                    'ContentsContinued' => $ContentsContinued,
                    'Slug' => $News->getSlug(),
                    'CreatedAt' => $News->getCreatedAt(),
                ];
            }
        }

        return $FilteredNews;
    }
}
