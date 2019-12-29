<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Player;
use App\Entity\Votes;
use App\Entity\Round;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\PlayerController;

/*
 * @Security("is_granted('ROLE_ADMIN')")
 */

class DefaultController extends AbstractController
{
    /**
     * Čístý start aplikace
     * @Route("/start")
     *
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
     * Určím náhodné pořadí hráčů pro dnešní večer.
     * žádné podvody :)
     * @Route("/randomize")
     */
    public function randomizeAction()
    {
		$em=$this->getDoctrine()->getManager();

		$players=$em->getRepository("App:Player")->findAll();
		foreach( $players as $player){
			$player->setPosition(rand(0,199));
			//$player->setDead(0);
			$em->persist($player);
		}
		$em->flush();
		return $this->listAction("Seznam hráčů");
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
     * @Route("/totals")
     */
    public function totalsAction(Request $request)
    {
		$em=$this->getDoctrine()->getManager();
		$data=$em->getRepository("App:Player")->findBy(array("dead"=>0),array("position"=>"ASC"));

		return $this->render(
			'default/list_totals.html.twig',array("players"=>$this->alivePlayers())
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
                if($round->getRoundNum()<3){
                	return $this->listAction("V tomto kole se ještě nevyřazuje!");


                }

                $min=1000000000;
                $cnt=0;
                $tokill=null;
                foreach($this->alivePlayers()as $p){
                    if($p->getTotalVotes()<$min){
                        $min=$p->getTotalVotes();
                        $tokill=$p;
                        $cnt=1;
                    }elseif($p->getTotalVotes()==$min){
                        $cnt=2;
                    }
                }
                if($cnt==1){
                	return $this->redirectToRoute("app_player_dead",array("DeadPlayer"=>$tokill->getId()));

                }
                else{

			return $this->listAction("Pro rovnost počtu hlasů do dalšího kola postupují všichni hráči");
		}



    }


    private function alivePlayers(){
		$em=$this->getDoctrine()->getManager();
		return $em->getRepository("App:Player")->findBy(array("dead"=>0),array("position"=>"ASC"));
	}

    /**
     * @Route("/list")
     */
    public function listAction($Title="",$Next="",$Round=0)
    {
		$em=$this->getDoctrine()->getManager();
		$data=$em->getRepository("App:Player")->findBy(array("dead"=>0),array("position"=>"ASC"));
		if ($Title=="") $Title= "Seznam hráčů";
		if ($Next=="") $Next= $this->generateUrl("app_default_nextround");
		return $this->render(
			'default/list_players.html.twig',array("players"=>$this->alivePlayers(),"round"=>$Round, "nadpis"=>$Title,"next"=>$Next)
		);
    }









    private function actualRound(){
		$data=$this->getDoctrine()->getRepository("App:Round")->findBy(
			array(),
			array("roundNum"=>"DESC")
		);
		foreach($data as $Row)		{
			return $Row;
		}
		return null;
	}

   private function  actualRoundVotes($RoundId){
		$data=$this->getDoctrine()->getRepository("App:Votes")->findBy(
			array("round"=>$RoundId

			),
			array("summed"=>"DESC")
		);

		return $data;

	}




    private function assignRawVotes( $RoundId){


        $em = $this->getDoctrine()->getManager();

        $RAW_QUERY = 'update fast_votes set round= :val where round = 0;';

        $statement = $em->getConnection()->prepare($RAW_QUERY);
        // Set parameters
        $statement->bindValue('val', $RoundId);
        $statement->execute();




    }


    /**
     * 	Vytvoří kolo a zobrazí hlavičku
     * @Route("/nextround")
     */
    public function nextroundAction()
    {
  		//$this->randomizeAction();
      $this->assignRawVotes(-2); //hide votes in table
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


  		return $this->listAction($new.". kolo",$this->generateUrl("app_default_round"),$new);
    }

    private function  getRawVotes( $RoundId){
 		$data=$this->getDoctrine()->getRepository("App:FastVotes")->findBy(
 			array("Round"=>$RoundId)
 		);

 		return $data;

 	}

    /**
     * pokud se nehlasovalo pro všechny hráče, hlasuje se pro ně
	 * jinak redirect na výsledky kola
     * @Route("/round")
     */
    public function roundAction()
    {
  		$round=$this->actualRound();
      $roundid=$round->getId();
  		if(is_null($round)){
  			return $this->redirectToRoute("app_default_nextround");
  		}
      $this->assignRawVotes($roundid);

		$players=$this->alivePlayers();

    $fastvotes=$this->getRawVotes($roundid);
    $results=array(0,0,0,0,0,0);

    //sessionfilter, use only last value from session , it is still possible to fake, but not too easy :)
    $filtered=array();
    foreach($fastvotes as $Vote){
      $filtered[$Vote->getSession()]=$Vote->getRawData();
    }


    $vtnum=0;
    foreach($filtered as $Vote){
      $vtnum++;
      $r=json_decode($Vote);
        foreach($r as $Key=>$Value){
          $results[$Key]+=$Value;
        }
    }

    $em=$this->getDoctrine()->getManager();
    $Pid=0;
    foreach($players as $Player){
      $V=new Votes();
      $V->setRound($round)->setPlayer($Player)->setSummed(round($results[$Pid]/($vtnum?$vtnum:1),2))  ;
      $em->persist($V);
      $Pid++;
    }

    $em->flush();
    //return new Response ("spočteno" . var_export($results));
		return $this->redirectToRoute("app_default_lvotes");


    }




}
