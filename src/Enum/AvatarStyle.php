<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum AvatarStyle: int  implements TranslatableInterface {

	case Carre = 0;
	case Rectangle = 1;

	public function trans(TranslatorInterface $translator, ?string $locale = null): string {

        return match ($this) {
			
            self::Carre  => $translator->trans('Text.Config.Carre', domain: 'User', locale: $locale),
            self::Rectangle => $translator->trans('Text.Config.Rectangle', domain: 'User', locale: $locale),
        };
    }
}
