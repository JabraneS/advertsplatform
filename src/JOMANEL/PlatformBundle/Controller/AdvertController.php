<?php

namespace JOMANEL\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RequestStack;

use JOMANEL\PlatformBundle\Entity\Advert;
use JOMANEL\PlatformBundle\Form\AdvertType;
use JOMANEL\PlatformBundle\Form\AdvertEditType;
use JOMANEL\PlatformBundle\Entity\Image;
use JOMANEL\PlatformBundle\Entity\Application;
use JOMANEL\PlatformBundle\Entity\Category;
use JOMANEL\PlatformBundle\Form\CategoryType;
use JOMANEL\PlatformBundle\Entity\Skill;

use JOMANEL\PlatformBundle\Entity\Post;
use JOMANEL\PlatformBundle\Form\PostType;






class AdvertController extends Controller{

    public function indexAction($page){

	    /*if ($page < 1) {
	      // On déclenche une exception NotFoundHttpException, cela va afficher
	      // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
	      //throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
	    }*/

	    // Ici je fixe le nombre d'annonces par page à 3
	    // Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
	    $nbPerPage = 4;

	    // Notre liste d'annonce en dur
	    $listAdverts = $this->getDoctrine()
			    			->getManager()
			    			->getRepository('JOMANELPlatformBundle:Advert')
			    			->getAllAdvertsWithPaginator($page, $nbPerPage)
	    ;

	    ////////
	    // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
	    $nbPages = ceil(count($listAdverts) / $nbPerPage);

	    // Si la page n'existe pas, on retourne une 404
	    if ($page > $nbPages) {
	      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
	    }

	    // On donne toutes les informations nécessaires à la vue
	    return $this->render('JOMANELPlatformBundle:Advert:index.html.twig', array(
	      'listAdverts' => $listAdverts,
	      'nbPages'     => $nbPages,
	      'page'        => $page,
	    ));
	    ////////

	    // Et modifiez le 2nd argument pour injecter notre liste
	    //return $this->render('JOMANELPlatformBundle:Advert:index.html.twig', array('listAdverts' => $listAdverts));
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


    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function addAction(Request $request){

    	/*
    	$locale = $request->getLocale();
 		echo $locale;
 		exit;
 		*/

    	// On récupère le service
	    $antispam = $this->container->get('jomanel_platform.antispam');

	    // Je pars du principe que $text contient le texte d'un message quelconque
	    $text = '...........................................................';
	    if ($antispam->isSpam($text)) {
	      throw new \Exception('Your message was detected as spam!');
	    }
	    
	    // Ici le message n'est pas un spam :
	   
	    $advert = new Advert();
	    $form   = $this->get('form.factory')->create(AdvertType::class, $advert);

	    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
		    $em = $this->getDoctrine()->getManager();
		    $ipClient = $this->container->get('request_stack')->getCurrentRequest()->getClientIp();
		    $advert->setIP($ipClient);
		    $em->persist($advert);
		    $em->flush();

		    $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

		    return $this->redirectToRoute('jomanel_platform_view', array('id' => $advert->getId()));
	    }

	    return $this->render('JOMANELPlatformBundle:Advert:add.html.twig', array('form' => $form->createView()));
	  
        
    }//fnc


   /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function editAction($id, Request $request){
	    
	    $em = $this->getDoctrine()->getManager();

	    $advert = $em->getRepository('JOMANELPlatformBundle:Advert')->find($id);

	    if (null === $advert) {
	      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
	    }

	    $form = $this->get('form.factory')->create(AdvertEditType::class, $advert);

	    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
	      	// Inutile de persister ici, Doctrine connait déjà notre annonce
	      	$em->flush();

	      	$request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

	      	return $this->redirectToRoute('jomanel_platform_view', array('id' => $advert->getId()));
	    }

	    return $this->render('JOMANELPlatformBundle:Advert:edit.html.twig', array(
	      	'advert' => $advert,
	      	'form'   => $form->createView(),
	    ));
	}


   /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function deleteAction(Request $request, $id){

	    $em = $this->getDoctrine()->getManager();

	    $advert = $em->getRepository('JOMANELPlatformBundle:Advert')->find($id);

	    if (null === $advert) {
	      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
	    }

	    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
	    // Cela permet de protéger la suppression d'annonce contre cette faille
	    $form = $this->get('form.factory')->create();

	    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
	      $em->remove($advert);
	      $em->flush();

	      $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

	      return $this->redirectToRoute('jomanel_platform_home');
	    }
	    
	    return $this->render('JOMANELPlatformBundle:Advert:delete.html.twig', array(
	      'advert' => $advert,
	      'form'   => $form->createView(),
	    ));
  	}


    public function menuAction($limit){
  
	    // X last adverts (X = 3)
	    $listAdverts = $this->getDoctrine()
	    					->getManager()
	    					->getRepository('JOMANELPlatformBundle:Advert')
	                        ->getXLastAdverts(3)
	    ;

	    return $this->render('JOMANELPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
    }//fnc


    
    /**
     * @Security("has_role('ROLE_USER') or has_role('ROLE_ADMIN')")
     */
    public function applyAction($id){
  
	    //=== find this advert by here id : 
	    $em = $this->getDoctrine()->getManager();

	    // On récupère l'annonce $id
	    $advert = $em->getRepository('JOMANELPlatformBundle:Advert')->find($id);

	    if (null === $advert) {
	      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
	    }
	    
	    //
	    //=== add application to this advert
	    //creating vew application 
	    $application = new Application();
        $application->setAuthor("jjjjoel1");
        $ipClient = $this->container->get('request_stack')->getCurrentRequest()->getClientIp();
        $application->setIP($ipClient);
        $application->setContent("mmmmotivé");
        
        //lier cet advert à cette application
	    $advert->addApplication($application);

	    //=== persisting and flushing
	    $em->persist($advert);
        $em->persist($application);

        // On déclenche l'enregistrement de toutes les catégories
        $em->flush();

	    //=== 
	    return $this->render('JOMANELPlatformBundle:Advert:applySucces.html.twig', array('advert' => $advert));
    }//fnc



   /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function purgeAction(){

	    // On récupère le service
	    $puger = $this->container->get('jomanel_platform.purger.advert');//.advert
	    $puger->purge(3);

	    //=== render
	    return $this->render('JOMANELPlatformBundle:Advert:test.html.twig', array('listIdsOfAdvertsWhichHaveApplications' => $listIdsOfAdvertsWhichHaveApplications));


    }//fnc


  
    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function removeappAction(){

    	// charger une annonce (2):
    	$em = $this->getDoctrine()->getManager();

	    // On récupère l'annonce $id
	    $app = $em->getRepository('JOMANELPlatformBundle:Application')->find(2);
	    //$img = $em->getRepository('JOMANELPlatformBundle:Image')->find(5);

		//la modifier :
		$em->remove($app);
	    //$em->remove($img);

		//l'enregistrer :
	    $em->flush();
	
	    //exit;
		
		return $this->render('JOMANELPlatformBundle:Advert:test2.html.twig');

    }


    
    //=============test=============//
    public function testAction(Request $request){ //\AppBundle\Entity\Post $post, 

    	$locale = $request->getLocale();
    	//echo $locale;exit;


    	$em = $this->getDoctrine()->getManager();

    	/********************************Enregistrement********************************/
    	/*$category = new CCategory;
	    $category->translate('fr')->setName('Chaussures');
	    $category->translate('en')->setName('Shoes');
	    $em->persist($category);

	    // In order to persist new translations, call mergeNewTranslations method, before flush
	    $category->mergeNewTranslations();

	    //l'enregistrer :
	    $em->flush();*/

		//$category->getCurrentLocale();

		//echo $category->getName();exit;
		//

	    //echo $category->translate('fr')->getName()."<br/>";
		//echo $category->translate('en')->getName();exit;

	    /*******************************Affichage/_local******************************/
		//$names = $em->getRepository('JOMANELPlatformBundle:CCategoryTranslation')->getAllNames($locale);//findAll();
		/*$listAdverts = $this->getDoctrine()
			    			->getManager()
			    			->getRepository('JOMANELPlatformBundle:Advert')
			    			->getAllAdvertsWithPaginator($page, $nbPerPage)
	    ;*/
		//$names->getCurrentLocale();
		//print_r($names);exit;
		//echo $Names;exit;

		///return $this->render('JOMANELPlatformBundle:Advert:test.html.twig', array("listNamesCCategory"=>$names));
		//////////////

		// create form to edit translations
		/*$ccategory = new CCategory();
        $form = $this->get('form.factory')->create(CCategoryType::class, $ccategory);
        // handle request data
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            // update DB
            $this->getDoctrine()->getManager()->flush();
            
            $this->addFlash('notce', 'Post has been successfully updated.');
            return $this->redirectToRoute('edit', ['id' => $post->getId()]);
        }
    
        // render form view
        return $this->render('JOMANELPlatformBundle:Advert:test.html.twig', ['form'  => $form->createView()]);*/

        /*$post = new Post();
		$post->translate('fr')->setTitle('Programmer');
		$post->translate('en')->setTitle('Programming');
		$em->persist($post);
		 
		// In order to persist new translations, call mergeNewTranslations method, before flush
		$post->mergeNewTranslations();
		$em->flush();
		 
		echo $post->translate('fr')->getTitle();
		echo $post->translate('en')->getTitle();exit;

		return $this->render('JOMANELPlatformBundle:Advert:test.html.twig', array("listNamesCCategory"=>$names));*/

		/*$post = new Post();

        // create form to edit translations
        $form = $this->get('form.factory')->create(PostType::class, $post);
        // handle request data
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            // update DB
            $this->getDoctrine()->getManager()->flush();
            
            $this->addFlash('notce', 'Post has been successfully updated.');
            return $this->redirectToRoute('jomanel_platform_test', ['id' => $post->getId()]);
        }
    
        // render form view
        return $this->render('JOMANELPlatformBundle:Advert:test.html.twig', [
            'form'  => $form->createView()
        ]);
        */

        //////////////////////////////////////////////////////////////////////

        // new CategoryTranslation()
        /*$categoryTrans = new CategoryTranslation();
        $categoryTrans-> setName('Génie Electrique');
        $categoryTrans-> setlocale('fr');
        

        // new Category() à CategoryTranslation()
        $category = new Category();

        // On lie la CategoryTranslation la Category
        $categoryTrans->setCategory($category);

        //persister :
	    $em->persist($categoryTrans);
        
        //
        $categoryTrans2 = new CategoryTranslation();
        $categoryTrans2->setName('Electrical Engineering');
        $categoryTrans2->setlocale('en');

        $categoryTrans2->setCategory($category);
        $em->persist($categoryTrans2);
        //


        //l'enregistrer :
	    $em->flush();
		*/
	    ///////////////////////
	    // X last adverts (X = 3)
	    $listCategories = $em->getRepository('JOMANELPlatformBundle:Category')
	                         ->getAllCategories($locale)
	    ;
	    //print_r($listCategories)."<br/>";//exit;

	    for ($i=0; $i <count($listCategories) ; $i++) { 
	    	$listCategories1[] = $listCategories[$i]['name_'.$locale];
	    }
	    //print_r($listCategories1);
	    //exit;
	    return $this->render('JOMANELPlatformBundle:Advert:test.html.twig', array('listCategories' => $listCategories1));


        exit;
        //return $this->render('JOMANELPlatformBundle:Advert:test.html.twig', [
        //    'form'  => $form->createView()
        //]);
    	
    }



}//class