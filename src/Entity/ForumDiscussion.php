<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumDiscussionRepository")
 * @UniqueEntity("titre", message="Ce titre existe déjà")
 */
class ForumDiscussion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120)
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_edition;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ForumCategorie", inversedBy="forumDiscussions", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="forumDiscussions", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumCommentaire", mappedBy="discussion", cascade={"remove"})
     */
    private $forumCommentaires;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_new_com;

    /**
     * @ORM\Column(type="integer")
     */
    private $affichage;

    public function __construct() {
        $this->date_creation = new \DateTime();
        $this->date_edition = new \DateTime();
        $this->date_new_com = new \DateTime();
        $this->forumCommentaires = new ArrayCollection();
        $this->affichage = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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

    public function getCategorie(): ?ForumCategorie
    {
        return $this->categorie;
    }

    public function setCategorie(?ForumCategorie $categorie): self
    {
        $this->categorie = $categorie;

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

    /**
     * @return Collection|ForumCommentaire[]
     */
    public function getForumCommentaires(): Collection
    {
        return $this->forumCommentaires;
    }

    public function addForumCommentaire(ForumCommentaire $forumCommentaire): self
    {
        if (!$this->forumCommentaires->contains($forumCommentaire)) {
            $this->forumCommentaires[] = $forumCommentaire;
            $forumCommentaire->setDiscussion($this);
        }

        return $this;
    }

    public function removeForumCommentaire(ForumCommentaire $forumCommentaire): self
    {
        if ($this->forumCommentaires->contains($forumCommentaire)) {
            $this->forumCommentaires->removeElement($forumCommentaire);
            // set the owning side to null (unless already changed)
            if ($forumCommentaire->getDiscussion() === $this) {
                $forumCommentaire->setDiscussion(null);
            }
        }

        return $this;
    }

    public function getDateNewCom(): ?\DateTimeInterface
    {
        return $this->date_new_com;
    }

    public function setDateNewCom(?\DateTimeInterface $date_new_com): self
    {
        $this->date_new_com = $date_new_com;

        return $this;
    }

    public function getAffichage(): ?int
    {
        return $this->affichage;
    }

    public function setAffichage(int $affichage): self
    {
        $this->affichage = $affichage;

        return $this;
    }
}
