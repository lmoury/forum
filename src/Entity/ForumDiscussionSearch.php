<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class ForumDiscussionSearch
{

    /**
     * @var string
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     */
    private $mot_cle;

    /**
     * @var datetime|null
     */
    private $date_creation;

    /**
     * @var boolean
     */
    private $titre;

    /**
     * @var ArrayCollection
     */
    private $users;

    /**
     * @var ArrayCollection
     */
    private $categories;

    /**
     * @var string|null
     */
    private $trier;


    public function __construct() {
        $this->users = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getMotCle(): ?string
    {
        return $this->mot_cle;
    }

    /**
     * @param string $mot_cle
     * @return self
     */
    public function setMotCle(string $mot_cle): self
    {
        $this->mot_cle = $mot_cle;
        return $this;
    }

    /**
     * @return datetime|null
     */
    public function getDateCreation()
    {
        return $this->date_creation;
    }

    /**
     * @param datetime|null $date_creation
     * @return self
     */
    public function setDateCreation($date_creation)
    {
        $this->date_creation = $date_creation;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getTitre(): ?bool
    {
        return $this->titre;
    }

    /**
     * @param boolean $titre
     * @return self
     */
    public function setTitre(bool $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers(): ArrayCollection
    {
        return $this->users;
    }

    /**
     * @param ArrayCollection $users
     */
    public function setUsers(ArrayCollection $users): void
    {
        $this->users = $users;
    }

    /**
     * @return ArrayCollection
     */
    public function getCategories(): ArrayCollection
    {
        return $this->categories;
    }

    /**
     * @param ArrayCollection $categories
     */
    public function setCategories(ArrayCollection $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * @return string|null
     */
    public function getTrier(): ?string
    {
        return $this->trier;
    }

    /**
     * @param string $trier
     * @return self
     */
    public function setTrier(string $trier): self
    {
        $this->trier = $trier;
        return $this;
    }

}
