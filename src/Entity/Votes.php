<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Votes
 *
 * @ORM\Table(name="votes")
 * @ORM\Entity(repositoryClass="App\Repository\VotesRepository")
 */
class Votes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="Summed", type="float")
     */
    private $summed;



    /**
     * @var int
     *
     * @ORM\Column(name="player_id", type="integer")
     */
    private $playerId;


	/**
     *
     * @ORM\ManyToOne	(targetEntity="Round", inversedBy="votes")
     * @ORM\JoinColumn	(name="round_id", referencedColumnName="id")
     */

    private $round;

	/**
     *
     * @ORM\ManyToOne	(targetEntity="Player", inversedBy="votes")
     * @ORM\JoinColumn	(name="player_id", referencedColumnName="id")
     */

    private $player;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set summed
     *
     * @param float $summed
     *
     * @return Votes
     */
    public function setSummed($summed)
    {
        $this->summed = $summed;

        return $this;
    }

    /**
     * Get summed
     *
     * @return float
     */
    public function getSummed()
    {
        return $this->summed;
    }

    public function __toString() {
        return ''.$this->summed;
        }

    /**
     * Set playerId
     *
     * @param integer $playerId
     *
     * @return Votes
     */
    public function setPlayerId($playerId)
    {
        $this->playerId = $playerId;

        return $this;
    }

    /**
     * Get playerId
     *
     * @return int
     */
    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * Set round
     *
     * @param \App\Entity\Round $round
     *
     * @return Votes
     */
    public function setRound(\App\Entity\Round $round = null)
    {
        $this->round = $round;

        return $this;
    }

    /**
     * Get round
     *
     * @return \App\Entity\Round
     */
    public function getRound()
    {
        return $this->round;
    }



    /**
     * Set player
     *
     * @param \App\Entity\Player $player
     *
     * @return Votes
     */
    public function setPlayer(\App\Entity\Player $player = null)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return \App\Entity\Player
     */
    public function getPlayer()
    {
        return $this->player;
    }
}
