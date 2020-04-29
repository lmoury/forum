<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Vich\Uploadable
 * @UniqueEntity("username", message="Nom déjà pris")
 * @UniqueEntity("email", message="Email déjà pris")
 */
class User implements UserInterface,\Serializable
{

    const SEXE = [
            0=> 'Inconnu',
            1=> 'Masculin',
            2=> 'Feminin'
    ];


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @var File|null
     * @Assert\Image(
     * mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     * )
     * @Vich\UploadableField(mapping="avatar_image", fileNameProperty="avatar")
     */
    private $imageFile;

    /**
    * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $avatar;

    /**
     * @ORM\Column(type="date")
     */
    private $date_naissance;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_inscription;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_visite;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumDiscussion", mappedBy="auteur", cascade={"remove"})
     */
    private $forumDiscussions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumCommentaire", mappedBy="auteur", cascade={"remove"})
     */
    private $forumCommentaires;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\UserRole", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Likes", mappedBy="user")
     */
    private $likesUser;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Likes", mappedBy="auteur")
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumDiscussionView", mappedBy="user")
     */
    private $forumDiscussionViewUser;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Conversation", mappedBy="expediteur")
     */
    private $expediteurs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ConversationUser", mappedBy="participant")
     */
    private $conversationUsers;


    public function __construct() {
        $this->date_inscription = new \DateTime();
        $this->date_visite = new \DateTime();
        $this->avatar= "avatarDefault.png";
        $this->forumDiscussions = new ArrayCollection();
        $this->forumCommentaires = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->likesUser = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->whoshasvisiteds = new ArrayCollection();
        $this->whosonlines = new ArrayCollection();
        $this->messageries = new ArrayCollection();
        $this->participant = new ArrayCollection();
        $this->forumDiscussionViewUser = new ArrayCollection();
        $this->expediteurs = new ArrayCollection();
        $this->conversationUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getSlug() {
        return (new Slugify())->slugify($this->username);
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSexe(): ?int
    {
        return $this->sexe;
    }

    public function setSexe(int $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getSexeType() {
        return self::SEXE[$this->sexe];
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->date_inscription;
    }

    public function setDateInscription(\DateTimeInterface $date_inscription): self
    {
        $this->date_inscription = $date_inscription;

        return $this;
    }

    public function getDateVisite(): ?\DateTimeInterface
    {
        return $this->date_visite;
    }

    public function setDateVisite(\DateTimeInterface $date_visite): self
    {
        $this->date_visite = $date_visite;

        return $this;
    }


    /**
    * Returns the roles granted to the user.
    *
    *     public function getRoles()
    *     {
    *         return ['ROLE_USER'];
    *     }
    *
    * Alternatively, the roles might be stored on a ``roles`` property,
    * and populated in any number of different ways when the user object
    * is created.
    *
    * @return (Role|string)[] The user roles
    */
    public function getRoles()
    {
        $role = $this->role->getRole();
        return [$role];
    }

    /**
    * Returns the salt that was originally used to encode the password.
    *
    * This can return null if the password was not encoded using a salt.
    *
    * @return string|null The salt
    */
    public function getSalt()
    {
        return null;
    }

    /**
    * Removes sensitive data from the user.
    *
    * This is important if, at any given point, sensitive information like
    * the plain-text password is stored on this object.
    */
    public function eraseCredentials()
    {
        return null;
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password
        ) = unserialize($serialized, ['allowed_classes' => false]);
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
            $forumDiscussion->setAuteur($this);
        }

        return $this;
    }

    public function removeForumDiscussion(ForumDiscussion $forumDiscussion): self
    {
        if ($this->forumDiscussions->contains($forumDiscussion)) {
            $this->forumDiscussions->removeElement($forumDiscussion);
            // set the owning side to null (unless already changed)
            if ($forumDiscussion->getAuteur() === $this) {
                $forumDiscussion->setAuteur(null);
            }
        }

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
            $forumCommentaire->setAuteur($this);
        }

        return $this;
    }

    public function removeForumCommentaire(ForumCommentaire $forumCommentaire): self
    {
        if ($this->forumCommentaires->contains($forumCommentaire)) {
            $this->forumCommentaires->removeElement($forumCommentaire);
            // set the owning side to null (unless already changed)
            if ($forumCommentaire->getAuteur() === $this) {
                $forumCommentaire->setAuteur(null);
            }
        }

        return $this;
    }

    public function getRole(): ?UserRole
    {
        return $this->role;
    }

    public function setRole(?UserRole $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function setImageFile(?File $imageFile): self
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->date_visite = new \DateTime();
        }
        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @return Collection|Like[]
     */
    public function getLikeUser(): Collection
    {
        return $this->likesUser;
    }

    public function addLikesUser(Likes $likesUser): self
    {
        if (!$this->likesUser->contains($likesUser)) {
            $this->likesUser[] = $likesUser;
            $likesUser->setUser($this);
        }

        return $this;
    }

    public function removeLikesUser(Likes $likesUser): self
    {
        if ($this->likesUser->contains($likesUser)) {
            $this->likesUser->removeElement($likesUser);
            // set the owning side to null (unless already changed)
            if ($likesUser->getUser() === $this) {
                $likesUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLikes(Likes $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setAuteur($this);
        }

        return $this;
    }

    public function removeLikes(Likes $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getAuteur() === $this) {
                $like->setAuteur(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->username;
        return self::SEXE[$this->sexe];
    }

    /**
     * @return Collection|ForumDiscussionView[]
     */
    public function getForumDiscussionViewUser(): Collection
    {
        return $this->forumDiscussionViewUser;
    }

    public function addForumDiscussionViewUser(ForumDiscussionView $forumDiscussionViewUser): self
    {
        if (!$this->forumDiscussionViewUser->contains($forumDiscussionViewUser)) {
            $this->forumDiscussionViewUser[] = $forumDiscussionViewUser;
            $forumDiscussionViewUser->setUser($this);
        }

        return $this;
    }

    public function removeForumDiscussionViewUser(ForumDiscussionView $forumDiscussionViewUser): self
    {
        if ($this->forumDiscussionViewUser->contains($forumDiscussionViewUser)) {
            $this->forumDiscussionViewUser->removeElement($forumDiscussionViewUser);
            // set the owning side to null (unless already changed)
            if ($forumDiscussionViewUser->getUser() === $this) {
                $forumDiscussionViewUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Conversation[]
     */
    public function getExpediteurs(): Collection
    {
        return $this->expediteurs;
    }

    public function addExpediteur(Conversation $expediteur): self
    {
        if (!$this->expediteurs->contains($expediteur)) {
            $this->expediteurs[] = $expediteur;
            $expediteur->setExpediteur($this);
        }

        return $this;
    }

    public function removeExpediteur(Conversation $expediteur): self
    {
        if ($this->expediteurs->contains($expediteur)) {
            $this->expediteurs->removeElement($expediteur);
            // set the owning side to null (unless already changed)
            if ($expediteur->getExpediteur() === $this) {
                $expediteur->setExpediteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConversationUser[]
     */
    public function getConversationUsers(): Collection
    {
        return $this->conversationUsers;
    }

    public function addConversationUser(ConversationUser $conversationUser): self
    {
        if (!$this->conversationUsers->contains($conversationUser)) {
            $this->conversationUsers[] = $conversationUser;
            $conversationUser->setParticipant($this);
        }

        return $this;
    }

    public function removeConversationUser(ConversationUser $conversationUser): self
    {
        if ($this->conversationUsers->contains($conversationUser)) {
            $this->conversationUsers->removeElement($conversationUser);
            // set the owning side to null (unless already changed)
            if ($conversationUser->getParticipant() === $this) {
                $conversationUser->setParticipant(null);
            }
        }

        return $this;
    }
}
