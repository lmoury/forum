<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConversationRepository")
 */
class Conversation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="expediteurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $expediteur;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     */
    private $titre;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ConversationUser", mappedBy="conversation")
     */
    private $conversationMessage;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $locked = false;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->conversationMessage = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExpediteur(): ?User
    {
        return $this->expediteur;
    }

    public function setExpediteur(?User $expediteur): self
    {
        $this->expediteur = $expediteur;

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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSlug() {
        return (new Slugify())->slugify($this->titre);
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

    /**
     * @return Collection|ConversationUser[]
     */
    public function getConversationMessage(): Collection
    {
        return $this->conversationMessage;
    }

    public function addConversationMessage(ConversationUser $conversationMessage): self
    {
        if (!$this->conversationMessage->contains($conversationMessage)) {
            $this->conversationMessage[] = $conversationMessage;
            $conversationMessage->setConversation($this);
        }

        return $this;
    }

    public function removeConversationMessage(ConversationUser $conversationMessage): self
    {
        if ($this->conversationMessage->contains($conversationMessage)) {
            $this->conversationMessage->removeElement($conversationMessage);
            // set the owning side to null (unless already changed)
            if ($conversationMessage->getConversation() === $this) {
                $conversationMessage->setConversation(null);
            }
        }

        return $this;
    }

    public function getLocked(): ?bool
    {
        return $this->locked;
    }

    public function setLocked(bool $locked): self
    {
        $this->locked = $locked;

        return $this;
    }
}
