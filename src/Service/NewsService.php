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
 
class NewsService {

    private ParameterBagInterface $Params;
    protected string $LocaleVars;
    private NewsRepository $NewsRepository;

    public function __construct(ParameterBagInterface $Params, NewsRepository $NewsRepository) {
        
        $this->Params = $Params;
        $this->LocaleVars = $this->Params->get('App.Vars.Locales');
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
        $FilteredNews = [];

        foreach ($NewsList as $News) {

            $Title = $News->getTitleByLang($this->LocaleVars);
            $Contents = $News->getContentsByLang($this->LocaleVars);
            $ContentsContinued = $News->getContentsContinuedByLang($this->LocaleVars);

            if ($Title !== null && $Contents !== null) {

                $FilteredNews[] = [
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
