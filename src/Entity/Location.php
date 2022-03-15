<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 */
class Location
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,unique = true)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity=Server::class, mappedBy="location")
     */
    private $servers;

    public function __construct()
    {
        $this->servers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Server>
     */
    public function getServers(): Collection
    {
        return $this->servers;
    }

    public function addServer(Server $server): self
    {
        if (!$this->servers->contains($server)) {
            $this->servers[] = $server;
            $server->setLocation($this);
        }

        return $this;
    }

    public function removeServer(Server $server): self
    {
        if ($this->servers->removeElement($server)) {
            // set the owning side to null (unless already changed)
            if ($server->getLocation() === $this) {
                $server->setLocation(null);
            }
        }

        return $this;
    }
}
