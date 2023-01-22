<?php

namespace App\Entity;

use App\Repository\FritteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FritteRepository::class)]
class Fritte extends Complement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return parent::getId();
    }
}
