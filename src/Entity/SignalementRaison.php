<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SignalementRaisonRepository")
 */
class SignalementRaison
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $raison;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateSignalement;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="signalementRaisons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $signaleur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Signalement", inversedBy="signalementRaisons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $signalement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(string $raison): self
    {
        $this->raison = $raison;

        return $this;
    }

    public function getDateSignalement(): ?\DateTimeInterface
    {
        return $this->dateSignalement;
    }

    public function setDateSignalement(\DateTimeInterface $dateSignalement): self
    {
        $this->dateSignalement = $dateSignalement;

        return $this;
    }

    public function getSignaleur(): ?User
    {
        return $this->signaleur;
    }

    public function setSignaleur(?User $signaleur): self
    {
        $this->signaleur = $signaleur;

        return $this;
    }

    public function getSignalement(): ?Signalement
    {
        return $this->signalement;
    }

    public function setSignalement(?Signalement $signalement): self
    {
        $this->signalement = $signalement;

        return $this;
    }
}
