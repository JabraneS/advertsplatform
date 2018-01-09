<?php


namespace JOMANEL\PlatformBundle\Purger;

use JOMANEL\PlatformBundle\Entity\Advert;
use JOMANEL\PlatformBundle\Repository\AdvertRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertPurger{

	private $em;
	//private $days;

	public function __construct(\Doctrine\ORM\EntityManager $em, $container){

		$this->em        = $em;
		$this->container = $container; // to get locale
	}

  	public function purge($days){

	    //1)****** Récupérer les annonces à supprimer ******
  		//=== get EM
  		//$em = $this->getDoctrine()->getManager();

	    // === get Ids of All adverts 
	    $listAdverts = $this->em->getRepository('JOMANELPlatformBundle:Advert')
	                            ->getAllAdverts()                 
	    ;
	    //print_r($listAdverts);exit;

	    //1')****** If there are no adverts in the db ******
	    if(count($listAdverts) == 0){

	    	$locale = $this->container->get('request_stack')->getCurrentRequest()->getLocale();

	    	$msgExc_fr = "Rien à purger ==> Pas encore d'annonces enregistrées !";
	    	$msgExc_en = "Nothing to purge ==> No adverts registered yet!";
	      	
	      	if($locale == "fr"){
	      		throw new NotFoundHttpException($msgExc_fr);
	      	}
	      	else{
	      		throw new NotFoundHttpException($msgExc_en);
	      	}
	    }//if

	    	//get Ids of all adverts 
	    $listIdsOfAdverts = [];
	    foreach ($listAdverts as $advert) {
		  $listIdsOfAdverts[] = $advert['id'];
		}

			//print_r($listIdsOfAdverts);//exit;
			//echo '<br/>';
	    


	    // === get Ids of adverts wich have applications
	    	//get adverts wich have applications
	    $listAdvertsWhichHaveApplications = $this->em->getRepository('JOMANELPlatformBundle:Advert')
	                                                 ->getAdvertsWhichHaveApplications() 
	                                                              
	    ;

	    	//get Ids of adverts wich have applications
	    $listIdsOfAdvertsWhichHaveApplications = [];
	    
	    foreach ($listAdvertsWhichHaveApplications as $advert) {
		  $listIdsOfAdvertsWhichHaveApplications[] = $advert['id'];
		}

			//print_r($listIdsOfAdvertsWhichHaveApplications);exit;

		$listIdsOfAdvertsWhichHaveApplications = array_unique($listIdsOfAdvertsWhichHaveApplications);
			//print_r($listIdsOfAdvertsWhichHaveApplications);//exit;
			//echo '<br/>';

	    //=== get Ids Of Adverts Which Not Have Applications 
	    	//comp
	    $listIdOfAdvertsWhichNotHaveApplications = array_diff($listIdsOfAdverts, $listIdsOfAdvertsWhichHaveApplications);
	    	//print_r($listIdOfAdvertsWhichNotHaveApplications);exit;

	    //=== get Ids Of Adverts Which Not Have Applications and which are old
	    $listAdvertsWhichNotHaveApplicationsAndOlds = $this->em->getRepository('JOMANELPlatformBundle:Advert')
	    										               ->getAdvertsWhichAreOld($listIdOfAdvertsWhichNotHaveApplications, $days)//3 = $days
	                                                       
	    ;
//exit;
	    $listIdsOfAdvertsWhichNotHaveApplicationsAndOld = [];
	    foreach ($listAdvertsWhichNotHaveApplicationsAndOlds as $advert) {
		  $listIdsOfAdvertsWhichNotHaveApplicationsAndOld[] = $advert['id'];
		}
	
			//print_r($listIdsOfAdvertsWhichNotHaveApplicationsAndOld);exit;


	    //2)****** Supprimer ces annonces ******
	    if(count($listIdsOfAdvertsWhichNotHaveApplicationsAndOld) == 0){
	    	
	    	$locale = $this->container->get('request_stack')->getCurrentRequest()->getLocale();

	    	$msgExc_fr = "Rien à purger.";
	    	$msgExc_en = "Nothing to purge.";
	      	
	      	if($locale == "fr"){
	      		throw new NotFoundHttpException($msgExc_fr);
	      	}
	      	else{
	      		throw new NotFoundHttpException($msgExc_en);
	      	}
	    }
	    
	    $advertsWhichNotHaveApplicationsAndOld = $this->em->getRepository('JOMANELPlatformBundle:Advert')
	                                                ->getAdvertsByIds($listIdsOfAdvertsWhichNotHaveApplicationsAndOld)
	                                                 
	    ;

//exit;
	    foreach ($advertsWhichNotHaveApplicationsAndOld as $advert) {
		  $this->em->remove($advert);
		}
	
		$this->em->flush(); // Exécute un DELETE sur $advert

  	}//fnc

}//class

