<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WhosonlineRepository")
 */
class Whosonline
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="whosonlines")
     */
    private $online;

    /**
     * @ORM\Column(type="integer")
     */
    private $onlineTime;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $onlineIp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOnline(): ?User
    {
        return $this->online;
    }

    public function setOnline(?User $online): self
    {
        $this->online = $online;

        return $this;
    }

    public function getOnlineTime(): ?int
    {
        return $this->onlineTime;
    }

    public function setOnlineTime(int $onlineTime): self
    {
        $this->onlineTime = $onlineTime;

        return $this;
    }

    public function getOnlineIp(): ?string
    {
        return $this->onlineIp;
    }

    public function setOnlineIp(?string $onlineIp): self
    {
        $this->onlineIp = $onlineIp;

        return $this;
    }
}
