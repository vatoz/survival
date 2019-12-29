<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FastVotesRepository")
 */
class FastVotes
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
    private $rawData;

    /**
     * @ORM\Column(type="integer")
     */
    private $Round;



    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Session;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRawData(): ?string
    {
        return $this->rawData;
    }

    public function setRawData(string $rawData): self
    {
        $this->rawData = $rawData;

        return $this;
    }

    public function getRound(): ?int
    {
        return $this->Round;
    }

    public function setRound(int $Round): self
    {
        $this->Round = $Round;

        return $this;
    }

    public function getSession(): ?string
    {
        return $this->Session;
    }

    public function setSession(string $Session): self
    {
        $this->Session = $Session;

        return $this;
    }
}
