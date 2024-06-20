<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum TextAlign: string implements TranslatableInterface {

    case Left = 'Left aligned';
    case Center = 'Center aligned';
    case Right = 'Right aligned';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string {

        return match ($this) {
            
            self::Left  => $translator->trans('Text.Align.Left', domain: 'Editor', locale: $locale),
            self::Center => $translator->trans('Text.Align.Center', domain: 'Editor', locale: $locale),
            self::Right  => $translator->trans('Text.Align.Right', domain: 'Editor', locale: $locale),
        };
    }
}
