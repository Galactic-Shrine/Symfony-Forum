<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum UserStatus: string implements TranslatableInterface {

    case ONLINE = 'Online';//En Ligne
    case ABSENT = 'Absent';
    case OCCUPIED = 'Occupied';//Occupé
    case INVISIBLE = 'Invisible';
    case OFFLINE = 'Offline';//Déconnecter
    
    public function trans(TranslatorInterface $translator, ?string $locale = null): string {

        return match ($this) {

            self::ONLINE => $translator->trans('Status.Online', domain: 'User', locale: $locale),
            self::ABSENT => $translator->trans('Status.Absent', domain: 'User', locale: $locale),
            self::OCCUPIED => $translator->trans('Status.Occupied', domain: 'User', locale: $locale),
            self::INVISIBLE => $translator->trans('Status.Invisible', domain: 'User', locale: $locale),
            self::OFFLINE => $translator->trans('Status.Offline', domain: 'User', locale: $locale),
        };
    }
}