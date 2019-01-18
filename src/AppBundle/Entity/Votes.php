<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Votes
 *
 * @ORM\Table(name="votes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VotesRepository")
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
     * @ORM\Column(name="Summed", type="integer")
     */
    private $summed;

    

    /**
     * @var int
     *
     * @ORM\Column(name="player_id", type="integer")
     */
    private $playerId;

	/**
     * @var array
     *
     * @ORM\Column(name="rawval", type="array")
     */
    private $rawVotes;

	
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
     * @param integer $summed
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
     * @return int
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
     * @param \AppBundle\Entity\Round $round
     *
     * @return Votes
     */
    public function setRound(\AppBundle\Entity\Round $round = null)
    {
        $this->round = $round;

        return $this;
    }

    /**
     * Get round
     *
     * @return \AppBundle\Entity\Round
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * Set rawVotes
     *
     * @param array $rawVotes
     *
     * @return Votes
     */
    public function setRawVotes($rawVotes)
    {
        $this->rawVotes = $rawVotes;

        return $this;
    }

    /**
     * Get rawVotes
     *
     * @return array
     */
    public function getRawVotes()
    {
        return $this->rawVotes;
    }

    /**
     * Set player
     *
     * @param \AppBundle\Entity\Player $player
     *
     * @return Votes
     */
    public function setPlayer(\AppBundle\Entity\Player $player = null)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return \AppBundle\Entity\Player
     */
    public function getPlayer()
    {
        return $this->player;
    }
}
