<?php

namespace App\Entity;

use App\Repository\FlightRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=FlightRepository::class)
 * @UniqueEntity(
 *     fields={"code"},
 *     message="This code is already in use."
 * )
 */
class Flight
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
    private $code;

    /**
     * @ORM\ManyToMany(targetEntity=Way::class)
     */
    private $way;


    public function __construct()
    {

        $this->way = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|Way[]
     */
    public function getWay(): Collection
    {
        return $this->way;
    }


    public function addWay(Way $way): self
    {
        if (!$this->way->contains($way)) {
            $this->way[] = $way;
        }

        return $this;
    }

    public function removeWay(Way $way): self
    {
        $this->way->removeElement($way);

        return $this;
    }

}
