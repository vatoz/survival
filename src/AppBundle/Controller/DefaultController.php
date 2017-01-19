<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Player;
use AppBundle\Entity\Votes;
use AppBundle\Entity\Round;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Controller\PlayerController;

class DefaultController extends Controller
{
    /**
     * Čístý start aplikace 
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig' );
    }
    
    
    /**
     * Druhá, vítací obrazovka. Nic speciálního nedělá
     * @Route("/welcome")
     */
    public function welcomeAction(Request $request)
    {
        return $this->render('default/welcome.html.twig' );
    }
    
	/**
	 * Obrazovka s testem hlasování. Jen ať si to porotci vyzkouší.
     * @Route("/testvote")
     */
    public function testAction()
    {
        return $this->render('default/voting_test.html.twig' );
    }
    
    /**
     * Určím náhodné pořadí hráčů pro dnešní večer.
     * žádné podvody :)
     * @Route("/randomize")
     */
    public function randomizeAction()
    {
		$em=$this->getDoctrine()->getManager();		
	
		$players=$em->getRepository("AppBundle:Player")->findAll();
		foreach( $players as $player){
			$player->setPosition(rand(0,199));
			$player->setDead(0);		
			$em->persist($player);
		}	
		$em->flush();
		return $this->listAction("Náhodné pořadí");
    }
    
    /**
     * @todo implement
     * @Route("/votes_list")
     */
    public function lvotesAction(Request $request)
    {
		$round= $this->actualRound();	
		$votes=$this->actualRoundVotes(
		$round->getId()
		);
		
		// replace this example code with whatever you need
        return $this->render('default/list_votes.html.twig'
		,array(
		"votes"=>$votes,
		"round"=>$round
		)
        
        );
    }
    
    
    
    /**
     * @todo implement
     * @Route("/kill")
     */
    public function killAction(Request $request)
    {
		$round= $this->actualRound();	
		$votes=$this->actualRoundVotes(
			$round->getId()
		);
		
		$last=$votes[count($votes)-1];
		$b=$votes[count($votes)-2];
		if($last->getSummed()<$b->getSummed()){
			return $this->redirectToRoute("app_player_dead",array("DeadPlayer"=>$last->getPlayer()->getId()));
		}else{
			
			return $this->listAction("Pro rovnost hlasů do dalšího kola postupují všichni hráči");
		}
		
		

    }
    
    
    private function alivePlayers(){
		$em=$this->getDoctrine()->getManager();
		return $em->getRepository("AppBundle:Player")->findBy(array("dead"=>0),array("position"=>"ASC"));
	}
    
    /**
     * @Route("/list")
     */
    public function listAction($Title="",$Next="")
    {
		$em=$this->getDoctrine()->getManager();
		$data=$em->getRepository("AppBundle:Player")->findBy(array("dead"=>0),array("position"=>"ASC"));
		if ($Title=="") $Title= "Seznam hráčů";
		if ($Next=="") $Next= $this->generateUrl("app_default_nextround");
		return $this->render(
			'default/list_players.html.twig',array("players"=>$this->alivePlayers(), "nadpis"=>$Title,"next"=>$Next)	
		);
    }
    

    
    
    
    
   
   
    
    private function actualRound(){
		$data=$this->getDoctrine()->getRepository("AppBundle:Round")->findBy(
			array(),
			array("roundNum"=>"DESC")
		);
		foreach($data as $Row)		{
			return $Row;
		}
		return null;
	}
    
    private function  actualRoundVotes($RoundId){
		$data=$this->getDoctrine()->getRepository("AppBundle:Votes")->findBy(
			array("round"=>$RoundId
			
			),
			array("summed"=>"DESC")
		);
		
		return $data;	
			
	}
    
    /**
     * 	Vytvoří kolo a zobrazí hlavičku
     * @Route("/nextround")
     */
    public function nextroundAction()
    {
		
		$p=$this->alivePlayers();
		if(count($p)==1){
			return $this->redirectToRoute("app_player_winner",array("Winner"=>$p[0]->getId()));			
		}
		
		$new=1;
		$data=$this->actualRound();
		if(!is_null($data)){
			$new = $data->getRoundNum()+1;
		}
		
		$v=new Round();
		$v->setRoundNum($new);
		$em=$this->getDoctrine()->getManager();
		$em->persist($v);
		$em->flush();
		return $this->listAction($new.". kolo",$this->generateUrl("app_default_round"));
    }
    
    /**
     * pokud se nehlasovalo pro všechny hráče, hlasuje se pro ně
	 * jinak redirect na výsledky kola	
     * @Route("/round")
     */
    public function roundAction()
    {
		$round=$this->actualRound();
		if(is_null($round)){
			return $this->redirectToRoute("app_default_nextround");
		}

		
		$players=$this->alivePlayers();
		$votes=$round->getVotes();
		
		//mám li dostatek hlasů
		if($votes->count()>=count($players)){
				
		}
		
		foreach($players as $player){
			$voteme=true;
			foreach($votes as $vote){
				if($vote->getPlayerId()==$player->getId())$voteme=false;
			}
			if($voteme){
				return $this->redirectToRoute("app_player_voting",array("Player"=>$player->getId()));
			}
		}
		
		
		return $this->redirectToRoute("app_default_lvotes");
		
	
    }
    
    
   
   
    
    /**
     * @Route("/votes/{Player}")
     */
    public function votesAction($Player,Request $Request)
    {	
		$round=$this->actualRound();
		$v=new Votes();
		$v->setRound($round);	
		$v->setPlayer(
		$this->getDoctrine()->getRepository("AppBundle:Player")->find($Player)
		);
		$v1=$Request->query->get("input_vote_1",-1);
		$v2=$Request->query->get("input_vote_2",-1);
		$v3=$Request->query->get("input_vote_3",-1);
		$v4=$Request->query->get("input_vote_4",-1);
		$v5=$Request->query->get("input_vote_5",-1);
		
		$v->setSummed($v1+$v2+$v3+$v4+$v5);
		//$v->setRound($Round);
		$v->setRawVotes(array($v1,$v2,$v3,$v4,$v5));
		$em=$this->getDoctrine()->getManager();
		$em->persist($v);
		$em->flush();
		
		return $this->redirectToRoute("app_default_round");
		
		}
    
    
    
    
	

    

}
