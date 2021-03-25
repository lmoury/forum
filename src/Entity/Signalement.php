<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SignalementRepository")
 */
class Signalement
{

    const TYPE = [
            1=> 'Discussion',
            2=> 'Commentaire',
            3=> 'chatbox'
    ];

    const STATUT = [
            1=> 'Ouvert',
            2=> 'Résolu'
    ];


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="signaleur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateMessage;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $lu = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SignalementRaison", mappedBy="signalement", cascade={"remove"})
     */
    private $signalementRaisons;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $idSignal;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="integer", options={"default" : 1})
     */
    private $statut  = 1;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_new_raison;

    public function __construct()
    {
        $this->signalementRaisons = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDateMessage(): ?\DateTimeInterface
    {
        return $this->dateMessage;
    }

    public function setDateMessage(\DateTimeInterface $dateMessage): self
    {
        $this->dateMessage = $dateMessage;

        return $this;
    }

    public function getLu(): ?bool
    {
        return $this->lu;
    }

    public function setLu(bool $lu): self
    {
        $this->lu = $lu;

        return $this;
    }

    /**
     * @return Collection|SignalementRaison[]
     */
    public function getSignalementRaisons(): Collection
    {
        return $this->signalementRaisons;
    }

    public function addSignalementRaison(SignalementRaison $signalementRaison): self
    {
        if (!$this->signalementRaisons->contains($signalementRaison)) {
            $this->signalementRaisons[] = $signalementRaison;
            $signalementRaison->setSignalement($this);
        }

        return $this;
    }

    public function removeSignalementRaison(SignalementRaison $signalementRaison): self
    {
        if ($this->signalementRaisons->contains($signalementRaison)) {
            $this->signalementRaisons->removeElement($signalementRaison);
            // set the owning side to null (unless already changed)
            if ($signalementRaison->getSignalement() === $this) {
                $signalementRaison->setSignalement(null);
            }
        }

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTypeType() {
        return self::TYPE[$this->type];
    }

    public function getIdSignal(): ?int
    {
        return $this->idSignal;
    }

    public function setIdSignal(int $idSignal): self
    {
        $this->idSignal = $idSignal;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(int $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getStatutType() {
        return self::STATUT[$this->statut];
    }

    public function getDateNewRaison(): ?\DateTimeInterface
    {
        return $this->date_new_raison;
    }

    public function setDateNewRaison(\DateTimeInterface $date_new_raison): self
    {
        $this->date_new_raison = $date_new_raison;

        return $this;
    }

}
