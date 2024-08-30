<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Controller;

use App\Service\ConfigService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;

#[Route(name: 'app_')]
class MainController extends AbstractController {

	private ParameterBagInterface $Params;
	//protected ConfigService $configService;
	protected string $LocaleVars;

	public function __construct(ParameterBagInterface $Params) {

		$this->Params = $Params;
		$this->LocaleVars = $this->Params->get('App.Vars.Locales');
	}

	#[Route(['/Index', '/index'], name: 'index')]
	public function index(): Response {

		/* exemple configService:

		$someConfigValue = $this->configService->getConfigValue('Site_Name');*/
		
		return $this->render('Index.twig', ['controller_name' => 'MainController',]);
	}

	/*#[Route(['/Legal', '/legal'], name: 'legal')]
	public function legacy(): Response {

		return $this->render('Legal.twig', ['controller_name' => 'MainController',]);
	}*/

	#[Route(path: ['Locale={locale}', 'locale={locale}'], name: 'change_locale')]
	public function ChangeLocale($locale, Request $Request): Response {
	
		$Request->setLocale($locale);
		// On stocke la locale dans la session
		$Request->getSession()->set($this->LocaleVars, $locale);
		//$Request->setcookie(name: $this->LocaleVars, value: $locale, expires: 0, path: '/', domain:'', secure: false, httponly: true);

		// On revient sur la page précédente
		return $this->redirect($Request->headers->get('referer'));
	}
}
