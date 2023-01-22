<?php

namespace App\Entity;

use App\Repository\ComplementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComplementRepository::class)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorMap(['fritte' => 'Fritte', 'boisson'=>'Boisson'])]

class Complement extends Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\OneToMany(mappedBy: 'complement', targetEntity: ComplementMenu::class)]
    private Collection $complementMenus;

    public function __construct()
    {
        $this->complementMenus = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return parent::getId();
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
            $complementMenu->setComplement($this);
        }

        return $this;
    }

    public function removeComplementMenu(ComplementMenu $complementMenu): self
    {
        if ($this->complementMenus->removeElement($complementMenu)) {
            // set the owning side to null (unless already changed)
            if ($complementMenu->getComplement() === $this) {
                $complementMenu->setComplement(null);
            }
        }

        return $this;
    }


}
