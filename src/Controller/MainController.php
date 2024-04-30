<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController, Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\{Request, Response};

#[Route(name: 'app_')]
class MainController extends AbstractController
{
	#[Route(['/Index', '/index'], name: 'index')]
	public function index(): Response
	{
		return $this->render('index.twig', [
			'controller_name' => 'MainController',
		]);
	}

	/*#[Route(['/Legal', '/legal'], name: 'legal')]
	public function legacy(): Response
	{
		return $this->render('Main/legal.twig', [
			'controller_name' => 'MainController',
		]);
	}*/

	#[Route(path: ['Locale={locale}', 'locale={locale}'], name: 'change_locale')]
	public function ChangeLocale($locale, Request $Request): Response {

		$Request->setLocale($locale);
		// On stocke la locale dans la session
		$Request->getSession()->set('_locale', $locale);

		// On revient sur la page précédente
		return $this->redirect($Request->headers->get('referer'));
	}
}
