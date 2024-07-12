<?php
namespace App\Twig\Functions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ReplaceExtension extends AbstractExtension {

	public function getFunctions(): array{

		return [
			
			new TwigFunction('Replace', [$this, 'replace']),
			new TwigFunction('replace', [$this, 'replace']),
		];
	}

	public function replace($Remplacer, $Par, $Source) {

		return str_replace($Remplacer, $Par, $Source);
	}
}
