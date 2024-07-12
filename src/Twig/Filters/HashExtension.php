<?php
namespace App\Twig\Filters;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class HashExtension extends AbstractExtension {

	public function getFilters(): array {

		return [
			// Si votre filtre génère du HTML SÉCURISÉ, vous devriez ajouter un troisième
			// paramètre: ['is_safe' => ['html']]
			// Référence: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
			// Filtre avec la première lettre du nom en majuscules
			new TwigFilter('Md5', [$this, 'MD5'], ['is_safe' => ['html']]),
			// Filtre avec la première lettre du nom en minuscule
			new TwigFilter('md5', [$this, 'MD5'], ['is_safe' => ['html']]),
			// Filtre avec la première lettre du nom en majuscules
			new TwigFilter('Sha1', [$this, 'SHA1'], ['is_safe' => ['html']]),
			// Filtre avec la première lettre du nom en minuscule
			new TwigFilter('sha1', [$this, 'SHA1'], ['is_safe' => ['html']]),
		];
	}

	/**
	 * MD5
	 *
	 * Cette fonction calcule le hash MD5 d'une chaîne donnée.
	 *
	 * @param string $string   La chaîne pour laquelle calculer le hash MD5.
	 * @param bool|null $binary (Optionnel) Si vrai (true), retourne le hash binaire. Par défaut, le hash hexadécimal est retourné.
	 *
	 * @return string          Le hash MD5 résultant.
	 */
	public function MD5(string $string, bool|null $binary = false): string  {

		// Appeler la fonction md5() de PHP pour calculer le hash MD5
		return md5(string: $string, binary: $binary);
	}

	/**
	 * SHA1
	 *
	 * Cette fonction calcule le hash SHA1 d'une chaîne donnée.
	 *
	 * @param string $string   La chaîne pour laquelle calculer le hash SHA1.
	 * @param bool|null $binary (Optionnel) Si vrai (true), retourne le hash binaire. Par défaut, le hash hexadécimal est retourné.
	 *
	 * @return string          Le hash SHA1 résultant.
	 */
	public function SHA1(string $string, bool|null $binary = false): string {

		// Appeler la fonction sha1() de PHP pour calculer le hash SHA1
		return sha1(string: $string, binary: $binary);
	}
}
