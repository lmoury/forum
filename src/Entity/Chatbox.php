<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatboxRepository")
 */
class Chatbox
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="chatboxes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $poster;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Signalement", mappedBy="chatbox")
     */
    private $signalChatbox;

    public function __construct()
    {
        $this->signalChatbox = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

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

    public function getPoster(): ?\DateTimeInterface
    {
        return $this->poster;
    }

    public function setPoster(\DateTimeInterface $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * @return Collection|Signalement[]
     */
    public function getSignalChatbox(): Collection
    {
        return $this->signalChatbox;
    }

    public function addSignalChatbox(Signalement $signalChatbox): self
    {
        if (!$this->signalChatbox->contains($signalChatbox)) {
            $this->signalChatbox[] = $signalChatbox;
            $signalChatbox->setChatbox($this);
        }

        return $this;
    }

    public function removeSignalChatbox(Signalement $signalChatbox): self
    {
        if ($this->signalChatbox->contains($signalChatbox)) {
            $this->signalChatbox->removeElement($signalChatbox);
            // set the owning side to null (unless already changed)
            if ($signalChatbox->getChatbox() === $this) {
                $signalChatbox->setChatbox(null);
            }
        }

        return $this;
    }
}
