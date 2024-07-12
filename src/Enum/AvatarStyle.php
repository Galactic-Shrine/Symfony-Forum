<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum AvatarStyle: int  implements TranslatableInterface {

	case Square = 0;
    case Rectangle = 1;
    case Rounned = 2;
    case Octogonal = 3;

	public function trans(TranslatorInterface $translator, ?string $locale = null): string {

        return match ($this) {
			
            self::Square  => $translator->trans('Text.Config.Square', domain: 'User', locale: $locale),
            self::Rectangle => $translator->trans('Text.Config.Rectangle', domain: 'User', locale: $locale),
            self::Rounned => $translator->trans('Text.Config.Rounned', domain: 'User', locale: $locale),
            self::Octogonal => $translator->trans('Text.Config.Octogonal',   domain: 'User', locale: $locale),
        };
    }
}
