<?php

namespace App\Entity;

use App\Repository\ForumRulesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: ForumRulesRepository::class)]
class ForumRules
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $Id = null;

    #[ORM\Column(length: 50)]
    private ?string $Lang = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Content = null;

    public function getId(): ?int
    {
        return $this->Id;
    }

    public function getLang(): ?string
    {
        return $this->Lang;
    }

    public function setLang(string $Lang): static
    {
        $this->Lang = $Lang;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(string $Content): static
    {
        $this->Content = $Content;

        return $this;
    }
}
