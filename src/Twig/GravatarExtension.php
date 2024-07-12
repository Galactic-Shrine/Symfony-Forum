<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GravatarExtension extends AbstractExtension {

	// Méthode pour définir les filtres à utiliser dans les templates Twig
	public function getFilters(): array {

		return [
			// Si votre filtre génère du HTML SÉCURISÉ, vous devriez ajouter un troisième
			// paramètre: ['is_safe' => ['html']]
			// Référence: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
			// Filtre avec la première lettre du nom en majuscules
			new TwigFilter('Gravatar', [$this, 'gravatar'], ['is_safe' => ['html']]),
			// Filtre avec la première lettre du nom en minuscule
			new TwigFilter('gravatar', [$this, 'gravatar'], ['is_safe' => ['html']]),
		];
	}

	// Méthode pour définir les fonctions à utiliser dans les templates Twig
	public function getFunctions(): array {

		return [
			// Fonction avec la première lettre du nom en majuscules
			new TwigFunction('Gravatar', [$this, 'gravatar']),
			// Fonction avec la première lettre du nom en minuscule
			new TwigFunction('gravatar', [$this, 'gravatar']),
		];
	}

	/**
	 * gravatar
	 *
	 * Cette fonction génère l'URL d'un avatar Gravatar en fonction de l'adresse e-mail fournie.
	 *
	 * @param string $Mail           L'adresse e-mail pour laquelle obtenir l'avatar Gravatar.
	 * @param int|null $Size         (Optionnel) La taille de l'avatar en pixels.
	 * @param string|null $Extention (Optionnel) L'extension de l'avatar (par exemple: "svg", "jpg", "png", etc.).
	 *
	 * @return string                L'URL de l'avatar Gravatar généré.
	 */
	public function gravatar(string $Mail, ?int $Size = 180, ?string $Extention = null): string {
		
		// Convertir l'adresse e-mail en minuscules et supprimer les espaces inutiles
		$Mail = strtolower(trim($Mail));
		// Obtenir le hash MD5 de l'adresse e-mail
		$MailMd5 = md5($Mail);
		// Définir l'extension de l'avatar, s'il est fourni
		$Extention = ($Extention == null) ? "" : "." . $Extention ;
		// Définir la taille de l'avatar, s'il est fourni
		$Size = ($Size == null) ? "" : "s=" . $Size ;
		
		// Retourner l'URL de l'avatar Gravatar généré
		return "https://s.gravatar.com/avatar/{$MailMd5}{$Extention}?{$Size}";
	}
}
