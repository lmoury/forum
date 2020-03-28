<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WhoshasvisitedRepository")
 */
class Whoshasvisited
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="whoshasvisiteds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $visiteur;

    /**
     * @ORM\Column(type="integer")
     */
    private $visited_time;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $visited_ip;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVisiteur(): ?User
    {
        return $this->visiteur;
    }

    public function setVisiteur(?User $visiteur): self
    {
        $this->visiteur = $visiteur;

        return $this;
    }

    public function getVisitedTime(): ?int
    {
        return $this->visited_time;
    }

    public function setVisitedTime(int $visited_time): self
    {
        $this->visited_time = $visited_time;

        return $this;
    }

    public function getVisitedIp(): ?string
    {
        return $this->visited_ip;
    }

    public function setVisitedIp(?string $visited_ip): self
    {
        $this->visited_ip = $visited_ip;

        return $this;
    }
}
