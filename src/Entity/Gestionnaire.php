<?php

namespace App\Entity;

use App\Repository\GestionnaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GestionnaireRepository::class)]
class Gestionnaire extends User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function __construct()
    {
        $this->setRoles(['GEST']);
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
