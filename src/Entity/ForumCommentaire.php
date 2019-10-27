<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumCommentaireRepository")
 */
class ForumCommentaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     */
    private $commentaire;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_edition;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ForumDiscussion", inversedBy="forumCommentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $discussion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="forumCommentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

    public function __construct() {
        $this->date_creation = new \DateTime();
        $this->date_edition = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateEdition(): ?\DateTimeInterface
    {
        return $this->date_edition;
    }

    public function setDateEdition(\DateTimeInterface $date_edition): self
    {
        $this->date_edition = $date_edition;

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

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }
}
