<?php

namespace App\Entity;

use App\Repository\WayRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=WayRepository::class)
 * @UniqueEntity(
 *     fields={"code"},
 *     message="This code is already in use."
 * )
 */
class Way
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Airport::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $departure;

    /**
     * @ORM\ManyToOne(targetEntity=Airport::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $destination;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeparture(): ?Airport
    {
        return $this->departure;
    }

    public function setDeparture(?Airport $departure): self
    {
        $this->departure = $departure;

        return $this;
    }

    public function getDestination(): ?Airport
    {
        return $this->destination;
    }

    public function setDestination(?Airport $destination): self
    {
        $this->destination = $destination;

        return $this;
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
}
