<?php

namespace App\Entity;

use App\Repository\ServerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServerRepository::class)
 * @ORM\Table(indexes={
 *     @ORM\Index(name="ram_search", columns={"ram_id"}),
 *     @ORM\Index(name="hdd_search", columns={"hdd_id"}),
 *     @ORM\Index(name="location_search", columns={"location_id"}),
 *     })
 */
class Server
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @ORM\ManyToOne(targetEntity=Ram::class, inversedBy="servers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ram;

    /**
     * @ORM\ManyToOne(targetEntity=Harddisk::class, inversedBy="servers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hdd;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="servers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getRam(): ?Ram
    {
        return $this->ram;
    }

    public function setRam(?Ram $ram): self
    {
        $this->ram = $ram;

        return $this;
    }

    public function getHdd(): ?Harddisk
    {
        return $this->hdd;
    }

    public function setHdd(?Harddisk $hdd): self
    {
        $this->hdd = $hdd;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getAllData(): array
    {
        return [
            'model'=>$this->getModel(),
            'ram'=>$this->getRam()->getName(),
            'hdd'=>$this->getHdd()->getName(),
            'location'=>$this->getLocation()->getAddress(),
            'price'=>$this->getPrice()
        ];
    }
}
