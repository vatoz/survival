<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Player;
use App\Entity\Votes;
use App\Entity\Round;
use App\Entity\FastVotes;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\PlayerController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class VotingController extends AbstractController
{

  private $session;

      public function __construct(SessionInterface $session)
      {
          $this->session = $session;
      }


  /*
  *return open round or null, cached
  */ 
  private function actualRound(){ 
    $cache = new FilesystemAdapter();
    $value = $cache->get('round', function (ItemInterface $item) {
      $item->expiresAfter(2);

      $data=$this->getDoctrine()->getRepository("App:Round")->findBy(
        array("isOpen"=>1),
        array("roundNum"=>"DESC")
      );
      foreach($data as $Row)		{
        return $Row;
      }
      return null;      
    });
    return $value;
    
  }

  /*
  * Its cached
  */ 
  private function actualRoundPlayers(){
        $cache = new FilesystemAdapter();

        $val=$cache->get('players', function (ItemInterface $item) {
        $item->expiresAfter(5); 
        $plids=json_decode($this->actualRound()->getPlayerDefinition());
        $players=array();

        $rp=$this->getDoctrine()->getManager()->getRepository("App:Player");
        
        foreach($plids as $id){
          $players[]=$rp->find($id);
        }
        return $players;
      });
      return $val;        
  }





  /**
       * @Route("/", name="homepage")
       *
   */
  public function welcomeAction(){
    $this->session->set('run','yes');
    return $this->render( 'voter/welcome.html.twig' );
  }

 
  /**
   * @Route("/voting")
   */
  public function offervotingAction()
  {
    $cache = new FilesystemAdapter();

    $round=$this->actualRound();
    if(is_null($round)){ //osoba se snazi hlasovat ve chvili, kdy nejsme v kole.
      $value = $cache->get('rebel', function (ItemInterface $item) {
        $item->expiresAfter(5);
        $rsp = $this->render('voter/rebel.html.twig');
        return $rsp->getContent();
      });
    return new Response($value);
    }
    



    $request = Request::createFromGlobals();
    if(is_array($request->get("vote",null))){

          $votes=$request->get("vote",null);
          $players=$this->actualRoundPlayers();
      if (count($players)==count($votes)){
            $add=true;
            foreach($votes as $vote){
              if(intval($vote <=5)&&intval($vote>-1)){
                //hodnota je v mezích  
              }else{
                  return new Response("Nelíbí se mi hodnota".$vote);
                $add=false;
              }
            }
            if($add){
              $em=$this->getDoctrine()->getManager();
              $K=new FastVotes();
              $K->setRawData(json_encode($votes));
              $K->setRound(0);
              $K->setSession($this->session->getId());

              $em->persist($K);
              $em->flush();

              $oksave = $cache->get('saved', function (ItemInterface $item) {
                  $item->expiresAfter(45);
                  $rsp = $this->render(
                    'voter/saved.html.twig'
                  );

                  return $rsp->getContent();

              });
              return new Response($oksave);
            }
      }
    }




    $value = $cache->get('voting', function (ItemInterface $item) {
        $item->expiresAfter(5);
        
        $players=$this->actualRoundPlayers();        
        $rsp = $this->render(
          'voter/list.html.twig',array("players"=>$players)
        );

        return $rsp->getContent();
    });

    return new Response($value);

  }



}
