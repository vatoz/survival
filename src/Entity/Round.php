<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Round
 *
 * @ORM\Table(name="round")
 * @ORM\Entity(repositoryClass="App\Repository\RoundRepository")
 */
class Round
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
     * @ORM\Column(name="RoundNum", type="integer")
     */
    private $roundNum;

    /**
     * @var int
     *
     * @ORM\Column(name="IsOpen", type="integer", options={"default" : 1})
     */
    private $isOpen;

   

	/**
     *
     * @ORM\OneToMany(targetEntity="Votes", mappedBy="round")
     */
    private $votes;

function __construct(){

	$this->votes=new ArrayCollection();
	}


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
     * Set roundNum
     *
     * @param integer $roundNum
     *
     * @return Round
     */
    public function setRoundNum($roundNum)
    {
        $this->roundNum = $roundNum;

        return $this;
    }

    /**
     * Get roundNum
     *
     * @return int
     */
    public function getRoundNum()
    {
        return $this->roundNum;
    }

    public function getIsOpen()
    {
        return $this->isOpen;
    }
    public function setIsOpen($isOpen)
    {
        $this->isOpen=$isOpen;
        return $this;
    }


    public function __toString() {
        return ''.$this->roundNum;
    }

    /**
     * Add vote
     *
     * @param \App\Entity\Votes $vote
     *
     * @return Round
     */
    public function addVote(\App\Entity\Votes $vote)
    {
        $this->votes[] = $vote;

        return $this;
    }

    /**
     * Remove vote
     *
     * @param \App\Entity\Votes $vote
     */
    public function removeVote(\App\Entity\Votes $vote)
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
}
