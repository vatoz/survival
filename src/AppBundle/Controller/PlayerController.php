<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Player;

class PlayerController extends Controller
{
    /**
     * @Route("/player/voting/{Player}")
     */
    public function votingAction($Player)
    {
		
		$data=$this->getDoctrine()->getRepository("AppBundle:Player")->find($Player);
		return $this->render('default/voting_player.html.twig',["player"=>$data] );
    }

	 /**
     * @Route("/player/winner/{Winner}")
     */
    public function winnerAction($Winner)
    {

		$data=$this->getDoctrine()->getRepository("AppBundle:Player")->find($Winner);
		return $this->render('default/winner.html.twig',["player"=>$data] );
    }

	
    /**
     * @Route("/player/add/{Male}/{PlayerName}")
     */
    private function addAction($PlayerName,$Male)
    {
		$p=new Player();
		$p->setPlayerName($PlayerName);
		$p->setDead(0);
		$p->setMale($Male);
		$p->setPhoto("");
		$em=$this->getDoctrine()->getManager();
		$em->persist($p);
		$em->flush();
		if($Male>0){
			return new Response("Přidán hráč ". $PlayerName);
		}else{
			return new Response("Přidána hráčka ". $PlayerName);
		}
    }


	/**
     * @Route("/player/dead/{DeadPlayer}")
     */
    public function deadAction($DeadPlayer)
    {
		//todo nastav že je mrtvý
		$data=$this->getDoctrine()->getRepository("AppBundle:Player")->find($DeadPlayer);
		$data->setDead(1);
		$em=$this->getDoctrine()->getManager();
		$em->persist($data);
		$em->flush();
        return $this->render('default/dead_player.html.twig',["player"=>$data] );
    }
   
}
