<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SignalementRepository")
 */
class Signalement
{
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
    private $raison;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_signal;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $lu = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ForumDiscussion", inversedBy="signalDiscussion")
     */
    private $discussion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ForumCommentaire", inversedBy="signalCommentaire")
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chatbox", inversedBy="signalChatbox")
     */
    private $chatbox;

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

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(string $raison): self
    {
        $this->raison = $raison;

        return $this;
    }

    public function getDateSignal(): ?\DateTimeInterface
    {
        return $this->date_signal;
    }

    public function setDateSignal(\DateTimeInterface $date_signal): self
    {
        $this->date_signal = $date_signal;

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

    public function getDiscussion(): ?ForumDiscussion
    {
        return $this->discussion;
    }

    public function setDiscussion(?ForumDiscussion $discussion): self
    {
        $this->discussion = $discussion;

        return $this;
    }

    public function getCommentaire(): ?ForumCommentaire
    {
        return $this->commentaire;
    }

    public function setCommentaire(?ForumCommentaire $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getChatbox(): ?Chatbox
    {
        return $this->chatbox;
    }

    public function setChatbox(?Chatbox $chatbox): self
    {
        $this->chatbox = $chatbox;

        return $this;
    }
}
