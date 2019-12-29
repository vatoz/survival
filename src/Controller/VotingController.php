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
    //round=$this->actualRound()  ;
    $request = Request::createFromGlobals();
    if(is_array($request->get("vote",null))){

    $votes=$request->get("vote",null);
    $value = $cache->get('players', function (ItemInterface $item) {
          $item->expiresAfter(20);
          $em=$this->getDoctrine()->getManager();
          $data=$em->getRepository("App:Player")->findBy(array("dead"=>0),array("position"=>"ASC"));
          $result=array();
          foreach($data as $pla){
            $result[]=$pla->getId();
          }
          return $result;
      });


      if (count($value)==count($votes)){

            $add=true;
            foreach($votes as $vote){
              if(intval($vote <=5)&&intval($vote>-1)){}else{
                  return new Response("Nelíbí se mi hodnota".$vote);
                $add=false;}
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
                  $item->expiresAfter(120);


                  $rsp = $this->render(
                    'voter/saved.html.twig'
                  );

                  return $rsp->getContent();

              });
              return new Response($oksave);


            }
      }
    }



    // The callable will only be executed on a cache miss.
    $value = $cache->get('voting', function (ItemInterface $item) {
        $item->expiresAfter(1);


        $em=$this->getDoctrine()->getManager();
        $data=$em->getRepository("App:Player")->findBy(array("dead"=>0),array("position"=>"ASC"));
        $rsp = $this->render(
          'voter/list.html.twig',array("players"=>$data)
        );

        return $rsp->getContent();

    });

    return new Response($value);

    // ... and to remove the cache key
    //$cache->delete('my_cache_key');



  }



}
