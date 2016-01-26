<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Round
 *
 * @ORM\Table(name="round")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoundRepository")
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

    /**
     * Add vote
     *
     * @param \AppBundle\Entity\Votes $vote
     *
     * @return Round
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
}
