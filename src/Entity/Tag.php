<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 */
class Tag
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
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ForumDiscussion", mappedBy="tags")
     */
    private $tagDiscussions;

    public function __construct()
    {
        $this->tagDiscussions = new ArrayCollection();
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

    public function getSlug() {
        return (new Slugify())->slugify($this->nom);
    }

    /**
     * @return Collection|ForumDiscussion[]
     */
    public function getTagDiscussions(): Collection
    {
        return $this->tagDiscussions;
    }

    public function addTagDiscussion(ForumDiscussion $tagDiscussion): self
    {
        if (!$this->tagDiscussions->contains($tagDiscussion)) {
            $this->tagDiscussions[] = $tagDiscussion;
            $tagDiscussion->addTag($this);
        }

        return $this;
    }

    public function removeTagDiscussion(ForumDiscussion $tagDiscussion): self
    {
        if ($this->tagDiscussions->contains($tagDiscussion)) {
            $this->tagDiscussions->removeElement($tagDiscussion);
            $tagDiscussion->removeTag($this);
        }

        return $this;
    }
}
