<?php

namespace App\Twig;

use App\Service\ConfigService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ConfigExtension extends AbstractExtension
{
    private $configService;

    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('GetConfig', [$this, 'getConfigValue']),
            new TwigFunction('getConfig', [$this, 'getConfigValue']),
        ];
    }

    public function getConfigValue(string $name): ?string
    {
        return $this->configService->getConfigValue($name);
    }
}