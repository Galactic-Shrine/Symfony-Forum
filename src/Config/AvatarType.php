<?php

namespace App\Config;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum AvatarType: string implements TranslatableInterface
{
    case GrAvatar = 'GrAvatar';
    case Uploadable = 'Uploadable';
    //case Right = 'Right aligned';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        // Translate enum from name (GrAvatar, Uploadable or Right)
        return $translator->trans($this->name, domain: 'User', locale: $locale);

        // Translate enum using custom labels
        return match ($this) {
            self::GrAvatar  => $translator->trans('Text.Config.GrAvatar', domain: 'User', locale: $locale),
            self::Uploadable => $translator->trans('Text.Config.Uploadable', domain: 'User', locale: $locale),
            //self::Right  => $translator->trans('Text.Config.Right', locale: $locale),
        };
    }
}
