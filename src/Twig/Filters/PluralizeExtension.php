<?php
namespace App\Twig\Filters;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PluraliserExtension extends AbstractExtension {

	public function getFilters(): array {

		return [
			// Si votre filtre génère du HTML SÉCURISÉ, vous devriez ajouter un troisième
			// paramètre: ['is_safe' => ['html']]
			// Référence: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
			// Filtre avec la première lettre du nom en majuscules
			new TwigFilter('Pluralize', [$this, 'pluralize']),
			// Filtre avec la première lettre du nom en minuscule
			new TwigFilter('pluralize', [$this, 'pluralize']),
		];
	}
	
	/**
	 * pluraliser
	 *
	 * Cette fonction permet de gérer la pluralisation des chaînes en fonction d'un compteur.
	 *
	 * @param int $Count          Le compteur utilisé pour déterminer la forme de la chaîne.
	 * @param string $Singular     La chaîne au singulier.
	 * @param string $Plural       La chaîne au pluriel.
	 * @param bool $Addedvalue  (Optionnel) Un booléen indiquant si une valeur ajoutée doit être incluse dans la chaîne singulière absolue.
	 * @param string|null $SingularPriorityApsolu  (Optionnel) La chaîne à retourner si le compteur est égal ou inférieur à zéro.
	 *
	 * @return string               La chaîne résultante en fonction du compteur fourni.
	 */
	public function pluralize(int $Count, string $Singular, string $Plural, bool $Addedvalue = false, string $SingularPriorityApsolu = null): string {
		
		// Si le compteur est supérieur à 1, retourner la chaîne plurielle avec la valeur du compteur
		if ($Count > 1){

			return str_replace('%Valeur%', $Count, $Plural);
		}
		// Si le compteur est inférieur ou égal à zéro et qu'une chaîne singulière absolue est fournie, la retourner
		else if ($Count <= 0 && null !== $SingularPriorityApsolu){

			return $SingularPriorityApsolu; // Aucun remplacement de chaîne n'est nécessaire pour le Singulier Apsolu
		} 
		// Si le compteur est inférieur ou égal à zéro, qu'une chaîne singulière absolue est fournie et que la valeur ajoutée est activée, 
		// retourner la chaîne singulière absolue avec la valeur du compteur
		else if ($Count <= 0 && null !== $SingularPriorityApsolu && false !== $Addedvalue){

			return str_replace('%Valeur%', $Count, $SingularPriorityApsolu);
		} 

		// Sinon, retourner la chaîne singulière avec la valeur du compteur
		return str_replace('%Valeur%', $Count, $Singular);
	}
}
