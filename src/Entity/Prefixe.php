<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrefixeRepository")
 */
class Prefixe
{

    const STYLE = [
            0=> 'style',
            1=> 'style1',
            2=> 'style2',
            3=> 'style3',
            4=> 'style4',
            5=> 'style5'
    ];


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $prefix;

    /**
     * @ORM\Column(type="integer")
     */
    private $couleur;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ForumCategorie", inversedBy="prefixes")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumDiscussion", mappedBy="prefixe")
     */
    private $prefixDiscu;

    /**
     * @ORM\Column(type="string", length=35, nullable=true)
     */
    private $icon;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->prefixDiscu = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix): self
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function getCouleur(): ?int
    {
        return $this->couleur;
    }

    public function setCouleur(int $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getCouleurType() {
        return self::STYLE[$this->couleur];
    }

    /**
     * @return Collection|ForumCategorie[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(ForumCategorie $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(ForumCategorie $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    /**
     * @return Collection|ForumDiscussion[]
     */
    public function getPrefixDiscu(): Collection
    {
        return $this->prefixDiscu;
    }

    public function addPrefixDiscu(ForumDiscussion $prefixDiscu): self
    {
        if (!$this->prefixDiscu->contains($prefixDiscu)) {
            $this->prefixDiscu[] = $prefixDiscu;
            $prefixDiscu->setPrefixe($this);
        }

        return $this;
    }

    public function removePrefixDiscu(ForumDiscussion $prefixDiscu): self
    {
        if ($this->prefixDiscu->contains($prefixDiscu)) {
            $this->prefixDiscu->removeElement($prefixDiscu);
            // set the owning side to null (unless already changed)
            if ($prefixDiscu->getPrefixe() === $this) {
                $prefixDiscu->setPrefixe(null);
            }
        }

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
}
