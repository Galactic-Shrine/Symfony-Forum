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
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
 
class CategoryService {

    private ParameterBagInterface $Params;
    protected string $LocaleVars;
    private CategoryRepository $CategoryRepository;

    public function __construct(ParameterBagInterface $Params, CategoryRepository $CategoryRepository) {
        
        $this->Params = $Params;
        $this->LocaleVars = $this->Params->get('App.Vars.Locales');
        $this->CategoryRepository = $CategoryRepository;
    }

    public function getByLang(): array {
        
        $CategoryList = $this->CategoryRepository->findAll();
        $FilteredCategory = [];

        foreach ($CategoryList as $Category) {

            $Name = $Category->getNameByLang($this->LocaleVars);
            $Description = $Category->getDescriptionByLang($this->LocaleVars);
                
            $FilteredCategory[] = [
                'Name' => $Name,
                'Description' => $Description,
                'Data' => $Category->getData(),
                'Slug' => $Category->getSlug(),
                'Position' => $Category->getPosition(),
            ];
        }

        return $FilteredCategory;
    }
}
