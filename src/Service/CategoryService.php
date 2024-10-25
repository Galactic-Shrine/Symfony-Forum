<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Service;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\RequestStack;
 
class CategoryService {

    private ?string $LocaleVars;
    private CategoryRepository $CategoryRepository;

    public function __construct(CategoryRepository $CategoryRepository, RequestStack $requestStack) {
        
        // Récupère la requête courante et la locale active
        $currentRequest = $requestStack->getCurrentRequest();
        if ($currentRequest) {

            $this->LocaleVars = $currentRequest->getLocale(); // ex: 'en', 'fr'
        } else {

            // Si pas de requête active, utilise une valeur par défaut
            $this->LocaleVars = 'en'; // Valeur par défaut si aucune locale trouvée
        }

        $this->CategoryRepository = $CategoryRepository;
    }

    public function getByLang(): array {
        
        $CategoryList = $this->CategoryRepository->findAll();
        $FilteredCategory = [];

        foreach ($CategoryList as $Category) {

            $Name = $Category->getNameByLang($this->LocaleVars);
            $Description = $Category->getDescriptionByLang($this->LocaleVars);
                
            $FilteredCategory[] = [
                'Id' => $Category->getId(),
                'Name' => $Name,
                'Description' => $Description,
                'Data' => $Category->getData(),
                'Controllers' => $Category->getControllers(),
                'Slug' => $Category->getSlug(),
                'Position' => $Category->getPosition(),
            ];
        }

        return $FilteredCategory;
    }
}
