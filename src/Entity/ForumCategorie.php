<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumCategorieRepository")
 */
class ForumCategorie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $categorie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $parent;

    /**
     * @ORM\Column(type="string", length=35, nullable=true)
     */
    private $icon;

    /**
     * @ORM\Column(type="integer")
     */
    private $ordre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumDiscussion", mappedBy="categorie")
     */
    private $forumDiscussions;

    public function __construct()
    {
        $this->forumDiscussions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getSlug() {
        return (new Slugify())->slugify($this->categorie);
    }

    public function getParent(): ?int
    {
        return $this->parent;
    }

    public function setParent(?int $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * @return Collection|ForumDiscussion[]
     */
    public function getForumDiscussions(): Collection
    {
        return $this->forumDiscussions;
    }

    public function addForumDiscussion(ForumDiscussion $forumDiscussion): self
    {
        if (!$this->forumDiscussions->contains($forumDiscussion)) {
            $this->forumDiscussions[] = $forumDiscussion;
            $forumDiscussion->setCategorie($this);
        }

        return $this;
    }

    public function removeForumDiscussion(ForumDiscussion $forumDiscussion): self
    {
        if ($this->forumDiscussions->contains($forumDiscussion)) {
            $this->forumDiscussions->removeElement($forumDiscussion);
            // set the owning side to null (unless already changed)
            if ($forumDiscussion->getCategorie() === $this) {
                $forumDiscussion->setCategorie(null);
            }
        }

        return $this;
    }
}
