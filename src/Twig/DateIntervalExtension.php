<?php
namespace App\Twig;

use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class DateIntervalExtension extends AbstractExtension {

	public function getFilters(): array {

		return [
			// If your filter generates SAFE HTML, you should add a third
			// parameter: ['is_safe' => ['html']]
			// Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
			new TwigFilter('Interval', [$this, 'DateInterval'], ['is_safe' => ['html']]),
			new TwigFilter('interval', [$this, 'DateInterval'], ['is_safe' => ['html']]),
		];
	}

	public function getFunctions(): array {

		return [
			new TwigFunction('Interval', [$this, 'DateInterval']),
			new TwigFunction('interval', [$this, 'DateInterval']),
		];
	}

	/**
	 * DateInterval
	 *
	 * Cette fonction calcule la différence en années entre deux dates.
	 *
	 * @param string $Origin    La date d'origine au format "Y-m-d H:i:s".
	 * @param string $Target    (Optionnel) La date cible au format "Y-m-d H:i:s". Par défaut, c'est "now".
	 *
	 * @return int              La différence en années entre les deux dates.
	 */
	public function DateInterval(string $Origin, string $Target = "now"): int {
		
		// Créer un objet DateTimeImmutable pour la date d'origine
		$origin = new \DateTimeImmutable($Origin);
		// Créer un objet DateTimeImmutable pour la date cible
		$target = new \DateTimeImmutable($Target);
		
		// Calculer la différence en années entre les deux dates et la retourner
		return $origin->diff($target)->y;//->format("m/d/Y H:i")
	}
}
