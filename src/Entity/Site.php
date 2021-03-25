<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SiteRepository::class)
 */
class Site
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $nom;


    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="site")
     */
    private $listSorties;

    /**
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="site")
     */
    private $participants;

    public function __construct()
    {
        $this->listSorties = new ArrayCollection();
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }


    /**
     * @return Collection|Sortie[]
     */
    public function getListSorties(): Collection
    {
        return $this->listSorties;
    }

    public function addListSorty(Sortie $listSorty): self
    {
        if (!$this->listSorties->contains($listSorty)) {
            $this->listSorties[] = $listSorty;
            $listSorty->setSite($this);
        }

        return $this;
    }

    public function removeListSorty(Sortie $listSorty): self
    {
        if ($this->listSorties->removeElement($listSorty)) {
            // set the owning side to null (unless already changed)
            if ($listSorty->getSite() === $this) {
                $listSorty->setSite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setSite($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getSite() === $this) {
                $participant->setSite(null);
            }
        }

        return $this;
    }
}
