<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum AvatarType: string implements TranslatableInterface {
    
    case GrAvatar = 'GrAvatar';
    case Uploadable = 'Uploadable';
    case Generated = 'Generated';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string {

        return match ($this) {
            
            self::GrAvatar  => $translator->trans('Text.Config.GrAvatar', domain: 'User', locale: $locale),
            self::Uploadable => $translator->trans('Text.Config.Uploadable', domain: 'User', locale: $locale),
            self::Generated  => $translator->trans('Text.Config.Generated', locale: $locale),
        };
    }
}
