<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu extends Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'menus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Burger $burger = null;



    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: ComplementMenu::class)]
    private Collection $complementMenus;

    #[ORM\Column]
    private ?bool $etat = null;

    public function __construct()
    {
        $this->complementMenus = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return parent::getId();
    }

    public function getBurger(): ?Burger
    {
        return $this->burger;
    }

    public function setBurger(?Burger $burger): self
    {
        $this->burger = $burger;

        return $this;
    }

    /**
     * @return Collection<int, ComplementMenu>
     */
    public function getComplementMenus(): Collection
    {
        return $this->complementMenus;
    }

    public function addComplementMenu(ComplementMenu $complementMenu): self
    {
        if (!$this->complementMenus->contains($complementMenu)) {
            $this->complementMenus->add($complementMenu);
            $complementMenu->setMenu($this);
        }

        return $this;
    }

    public function removeComplementMenu(ComplementMenu $complementMenu): self
    {
        if ($this->complementMenus->removeElement($complementMenu)) {
            // set the owning side to null (unless already changed)
            if ($complementMenu->getMenu() === $this) {
                $complementMenu->setMenu(null);
            }
        }

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
