<?php

namespace JOMANEL\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use JOMANEL\PlatformBundle\Entity\Advert;
use JOMANEL\PlatformBundle\Entity\Image;
//use JOMANEL\PlatformBundle\Entity\Application;
use JOMANEL\PlatformBundle\Entity\Category;
use JOMANEL\PlatformBundle\Entity\Skill;

//use JOMANEL\PlatformBundle\Repository\AdvertRepository;


class AdvertController extends Controller{

    public function indexAction($page){

	    /*if ($page < 1) {
	      // On déclenche une exception NotFoundHttpException, cela va afficher
	      // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
	      //throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
	    }*/

	    // Ici, on récupérera la liste des annonces, puis on la passera au template

	    // Notre liste d'annonce en dur
	    $listAdverts = array(
	      array(
	        'title'   => 'Recherche développpeur Symfony',
	        'id'      => 1,
	        'author'  => 'Alexandre',
	        'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
	        'date'    => new \Datetime()),
	      array(
	        'title'   => 'Mission de webmaster',
	        'id'      => 2,
	        'author'  => 'Hugo',
	        'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
	        'date'    => new \Datetime()),
	      array(
	        'title'   => 'Offre de stage webdesigner',
	        'id'      => 3,
	        'author'  => 'Mathieu',
	        'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
	        'date'    => new \Datetime())
	    );

	    // Et modifiez le 2nd argument pour injecter notre liste
	    return $this->render('JOMANELPlatformBundle:Advert:index.html.twig', array('listAdverts' => $listAdverts));
    }//fnc


    public function viewAction($id){

    	$em = $this->getDoctrine()->getManager();

	    // On récupère l'annonce $id
	    $advert = $em->getRepository('JOMANELPlatformBundle:Advert')->find($id);

	    if (null === $advert) {
	      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
	    }

	    // On récupère la liste des candidatures de cette annonce
	    $listApplications = $em
	      ->getRepository('JOMANELPlatformBundle:Application')
	      ->findBy(array('advert' => $advert))
	    ;

	    return $this->render('JOMANELPlatformBundle:Advert:view.html.twig', array(
	      'advert'           => $advert,
	      'listApplications' => $listApplications
	    ));

    }//fnc


    public function addAction(Request $request){

    	// On récupère le service
	    $antispam = $this->container->get('jomanel_platform.antispam');

	    // Je pars du principe que $text contient le texte d'un message quelconque
	    $text = '...........................................................';
	    if ($antispam->isSpam($text)) {
	      throw new \Exception('Your message was detected as spam!');
	    }
	    
	    // Ici le message n'est pas un spam :
	   
	    // Création de l'entité Advert
	    $advert = new Advert();
	    $advert->setTitle('Recherche développeur Symfony2.');
	    $advert->setAuthor('Alexandre2');
	    $advert->setContent("Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…2");

	    // Mettre une image à cet Advert
	    $image = new Image();
	    $image->setUrl('http://zyzixun.net/data/out/55/3252930-electrical-engineering-wallpapers.jpg');//('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');//('../imgs/electricalengineering.jpg');
	    $image->setAlt('ImgdataprEngineer');

	    // Mettre une category à cet Advert
	    $category = new Category();
	    $category->setName('Informatique');

	    /*// Mettre une skill à cet Advert
	    $skill = new Skill();
	    $skill->setName('c++');*/

	    //=>
	    // On lie l'advert à l'image
	    $advert->setImage($image);
	    // On lie l'advert à la catégorie
	    $advert->addCategory($category);
	    

	    // On récupère l'EntityManager
	    $em = $this->getDoctrine()->getManager();

	    // Étape 1 : On « persiste » l'entité
	    $em->persist($advert);

	    // Étape 1 ter : pour cette relation pas de cascade lorsqu'on persiste Advert, car la relation est
	    // définie dans l'entité Application et non Advert. On doit donc tout persister à la main ici.
	    //$em->persist($application1);
	    //$em->persist($application2);

	    // Étape 2 : On « flush » tout ce qui a été persisté avant
	    $em->flush();
	    //////

    	// La gestion d'un formulaire est particulière, mais l'idée est la suivante :
	    // Si la requête est en POST, c'est que le visiteur a soumis le formulaire
	    if ($request->isMethod('POST')) {
	      // Ici, on s'occupera de la création et de la gestion du formulaire

	      $request->getSession()->getFlashBag()->add('notice', 'advert well recorded.');

	      // Puis on redirige vers la page de visualisation de cettte annonce
	      return $this->redirectToRoute('jomanel_platform_view', array('id' => 5));
	    }

	    // Si on n'est pas en POST, alors on affiche le formulaire
	    return $this->render('JOMANELPlatformBundle:Advert:add.html.twig', array('advert' => $advert));
        
    }//fnc


    public function editAction($id, Request $request){

    	// Ici, on récupérera l'annonce correspondante à $id

	    // Même mécanisme que pour l'ajout
	    if ($request->isMethod('POST')) {
	      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modified.');

	      return $this->redirectToRoute('jomanel_platform_view', array('id' => 5));
	    }

	    ///// exemple pour ajouter une annonce existante à plusieurs catégories existantes
	     $em = $this->getDoctrine()->getManager();

	    // On récupère l'annonce $id
	    $advert = $em->getRepository('JOMANELPlatformBundle:Advert')->find($id);

	    if (null === $advert) {
	      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
	    }

	    // La méthode findAll retourne toutes les catégories de la base de données
	    $listCategories = $em->getRepository('JOMANELPlatformBundle:Category')->findAll();

	    // On boucle sur les catégories pour les lier à l'annonce
	    foreach ($listCategories as $category) {
	      $advert->addCategory($category);
	    }

	    // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
	    // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine

	    // Étape 2 : On déclenche l'enregistrement
	    $em->flush();
	    /////

	    $advert = array(
	      'title'   => 'Recherche développpeur Symfony',
	      'id'      => $id,
	      'author'  => 'Alexandre',
	      'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
	      'date'    => new \Datetime()
	    );

	    return $this->render('JOMANELPlatformBundle:Advert:edit.html.twig', array(
	      'advert' => $advert
	    ));
        
    }//fnc


    public function deleteAction($id){
    
    	/////exemple pour enlever toutes les catégories d'une annonce
    	$em = $this->getDoctrine()->getManager();

	    // On récupère l'annonce $id
	    $advert = $em->getRepository('JOMANELPlatformBundle:Advert')->find($id);

	    if (null === $advert) {
	      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
	    }

	    // On boucle sur les catégories de l'annonce pour les supprimer
	    foreach ($advert->getCategories() as $category) {
	      $advert->removeCategory($category);
	    }

	    // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
	    // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine

	    // On déclenche la modification
	    $em->flush();
    	/////

	    return $this->render('JOMANELPlatformBundle:Advert:delete.html.twig', array('id'=>$id));
        
    }//fnc


    public function menuAction($limit){
  
	    // On fixe en dur une liste ici, bien entendu par la suite
	    // on la récupérera depuis la BDD !
	    $listAdverts = array(
	      array('id' => 1, 'title' => 'Recherche développeur Symfony'),
	      array('id' => 2, 'title' => 'Mission de webmaster'),
	      array('id' => 3, 'title' => 'Offre de stage webdesigner')
	    );

	    return $this->render('JOMANELPlatformBundle:Advert:menu.html.twig', array(
	      // Tout l'intérêt est ici : le contrôleur passe
	      // les variables nécessaires au template !
	      'listAdverts' => $listAdverts
	    ));
    }//fnc


   



    public function testAction(){
    	
    	//==== AdvertRepository : getAllAdverts()
    	/*
    	$listAdverts = $this->getDoctrine()
					    	->getManager()
					    	->getRepository('JOMANELPlatformBundle:Advert')
					    	->getAllAdverts()
					    	;

		return $this->render('JOMANELPlatformBundle:Advert:test.html.twig', array('listAdverts'=>$listAdverts));
		*/

		//==== AdvertRepository : getAdvertsOfOneCategory($categoryName)
		/*$listAdverts = $this->getDoctrine()
					    	->getManager()
					    	->getRepository('JOMANELPlatformBundle:Advert')
					    	->getAdvertsOfOneCategory("Génie Electrique")
					    	;

		return $this->render('JOMANELPlatformBundle:Advert:test.html.twig', array('listAdverts'=>$listAdverts));
		*/

    	//==== AdvertRepository : getAdvertWithCategories(array $categoryNames)
    	/*
    	$listAdverts = $this->getDoctrine()
			    	    	->getManager()
			    			->getRepository('JOMANELPlatformBundle:Advert')
			    			->getAdvertWithCategories(array('Génie Informatique', 'Génie Electrique'));
			    			 
	    return $this->render('JOMANELPlatformBundle:Advert:test.html.twig', array('listAdverts' => $listAdverts));
	    */ 
	    
	    //============================================================================================================//
		
        //==== ApplicationRepository : getApplicationsWithAdvert($limit) : X the last ones
        /*
        $listApplications =  $this->getDoctrine()
			    			 ->getManager()
			    			 ->getRepository('JOMANELPlatformBundle:Application')
			    			 ->getApplicationsWithAdvert(3);

		return $this->render('JOMANELPlatformBundle:Advert:test.html.twig', array('listApplications' => $listApplications));
		*/

		//==== ApplicationRepository : getApplicationsOfanAvert($advertTitle)
    	
    	$listApplications = $this->getDoctrine()
			    	    	->getManager()
			    			->getRepository('JOMANELPlatformBundle:Application')
			    			->getApplicationsOfanAvert("Recherche développeur Symfony3.");
			    			 
	    return $this->render('JOMANELPlatformBundle:Advert:test.html.twig', array('listApplications' => $listApplications));

    }//fnc


}//class