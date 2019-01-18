<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Player
 *
 * @ORM\Table(name="player")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlayerRepository")
 */
class Player
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
     * @var string
     *
     * @ORM\Column(name="PlayerName", type="string", length=255)
     */
    private $playerName;

    /**
     * @var int
     *
     * @ORM\Column(name="Dead", type="integer")
     */
    private $dead;

	/**
     * @var int
     *
     * @ORM\Column(name="Position", type="integer")
     */
    private $position;


	/**
     * @var int
     *
     * @ORM\Column(name="Male", type="integer")
     */
    private $male;


    /**
     * @var string
     *
     * @ORM\Column(name="Photo", type="string", length=255)
     */
    private $photo;

	/**
     *
     * @ORM\OneToMany(targetEntity="Votes", mappedBy="player")
     */
    private $votes;
    
    /**
     * @var int
     *
     * @ORM\Column(name="TotalVotes", type="integer", options={"default":"0"})
     */
    private $totalVotes;



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
     * Set playerName
     *
     * @param string $playerName
     *
     * @return Player
     */
    public function setPlayerName($playerName)
    {
        $this->playerName = $playerName;

        return $this;
    }

    /**
     * Get playerName
     *
     * @return string
     */
    public function getPlayerName()
    {
        return $this->playerName;
    }

    /**
     * Set dead
     *
     * @param integer $dead
     *
     * @return Player
     */
    public function setDead($dead)
    {
        $this->dead = $dead;

        return $this;
    }

    /**
     * Get dead
     *
     * @return int
     */
    public function getDead()
    {
        return $this->dead;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Player
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set male
     *
     * @param integer $male
     *
     * @return Player
     */
    public function setMale($male)
    {
        $this->male = $male;

        return $this;
    }

    /**
     * Get male
     *
     * @return integer
     */
    public function getMale()
    {
        return $this->male;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Player
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add vote
     *
     * @param \AppBundle\Entity\Votes $vote
     *
     * @return Player
     */
    public function addVote(\AppBundle\Entity\Votes $vote)
    {
        $this->votes[] = $vote;

        return $this;
    }

    /**
     * Remove vote
     *
     * @param \AppBundle\Entity\Votes $vote
     */
    public function removeVote(\AppBundle\Entity\Votes $vote)
    {
        $this->votes->removeElement($vote);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVotes()
    {
        return $this->votes;
    }
    
    public function getTotalVotes(){
    $result=0;
    foreach($this->getVotes() as $v){
    $result+=$v->getSummed();
    }
    $this->totalVotes=$result;
    return $result;
    }
    
    public function setTotalVotes($v){
        
    $this->totalVotes=$v;
    }
    public function __toString() {
     return $this->playerName;
    }
    
    
}
