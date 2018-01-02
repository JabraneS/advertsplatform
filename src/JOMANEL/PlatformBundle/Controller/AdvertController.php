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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;//roles






class AdvertController extends Controller{

    public function indexAction($page, Request $request){

	    $locale = $request->getLocale();
	    $em     = $this->getDoctrine()->getManager();

	    $nbPerPage = 4;

	    // Notre liste d'annonce en dur
	    $listAdverts = $em->getRepository('JOMANELPlatformBundle:Advert')
			    		  ->getAllAdvertsWithPaginator($page, $nbPerPage)
	    ;
	    //str_replace("title_fr", replace, subject)
	    //print_r($listAdverts);exit;
	    
	    ////////
	    if ($locale == 'fr') {
	    	for ($i=0; $i < count($listAdverts); $i++) { 
		    	//$idsAdverts[$listAdverts[$i]['id']] = $listAdverts[$i];
		    	unset($listAdverts[$i]['title_en']);
		    }
	    }else{
	    	for ($i=0; $i < count($listAdverts); $i++) { 
		    	//$idsAdverts[$listAdverts[$i]['id']] = $listAdverts[$i];
		    	unset($listAdverts[$i]['title_fr']);
		    }
	    }
	    //print_r($listAdverts);exit;

	    ////////

	    // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
	    $nbPages = ceil(count($listAdverts) / $nbPerPage);
	    //echo $nbPages;exit;

	    // Si la page n'existe pas, on retourne une 404
	    if ($page > $nbPages) {
	      //throw $this->createNotFoundException("La page ".$page." n'existe pas.");
	      $nbPages = null;
	    }

	    // On donne toutes les informations nécessaires à la vue
	    return $this->render('JOMANELPlatformBundle:Advert:index.html.twig', array(
	      'listAdverts' => $listAdverts,
	      'nbPages'     => $nbPages,
	      'page'        => $page,
	    ));
	    ////////

    }//fnc


    public function viewAction(Advert $advert){

    	/*
    	$em = $this->getDoctrine()->getManager();

	    // On récupère l'annonce $id
	    $advert = $em->getRepository('JOMANELPlatformBundle:Advert')->find($id);//findAdvertById($id);
	    //print_r($advert);exit;

	    //advertCategories


	    if (null === $advert) {
	      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
	    	$advert = null;
	    }
	    */

	    
	    return $this->render('JOMANELPlatformBundle:Advert:view.html.twig', array('advert'=> $advert));

    }//fnc


    
    public function addAction(Request $request){

	    $locale = $request->getLocale();

	    //=== If there is no category => Adding at least one to continue ===//
	    $em = $this->getDoctrine()->getManager();
	    $listIdsCategories = $em->getRepository('JOMANELPlatformBundle:Category')->getAllIdsCategories();
	    //print_r($listIdsCategories);exit;
	    
	    if ( count($listIdsCategories) == 0 ) {
	    	
	    	$msgExc_fr = "Il n'y a aucune catégorie, ajouter au moins une avant d'ajouter l'annonce.";
	    	$msgExc_en = "There is no category, add at least one before adding the advert.";
	      	
	      	if($locale == "fr"){
	      		throw new NotFoundHttpException($msgExc_fr);
	      	}
	      	else{
	      		throw new NotFoundHttpException($msgExc_en);
	      	}
	    }
	    ////
	   
	    //=== Add advert form ===//
	    $advert = new Advert();
	    $form   = $this->get('form.factory')->create(AdvertType::class, $advert, array('locale' => $locale));

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


    public function editAction(Advert $advert, Request $request){
	    
	    $locale = $request->getLocale();
	    $em = $this->getDoctrine()->getManager();
	    /*
	    $em = $this->getDoctrine()->getManager();

	    $advert = $em->getRepository('JOMANELPlatformBundle:Advert')->find($id);

	    if (null === $advert) {
	      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
	    }
	    */

	    $form = $this->get('form.factory')->create(AdvertEditType::class, $advert, array('locale' => $locale));

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


 
    public function deleteAction(Advert $advert, Request $request){

	    $em = $this->getDoctrine()->getManager();
    	
    	//=== get applications for this advert
	    $arrayApplicationsObj = $advert->getApplications();

	    //=== On crée un formulaire vide, qui ne contiendra que le champ CSRF
	    // Cela permet de protéger la suppression d'annonce contre cette faille
	    $form = $this->get('form.factory')->create();

	    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
	      
	    	for($i=0; $i<count($arrayApplicationsObj); $i++){
	      		$em->remove($arrayApplicationsObj[$i]);
	      	}

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


    public function menuAction(Request $request){
  
	    $limite = 3;
	    $locale = $request->getLocale();
	    $em     = $this->getDoctrine()->getManager();

	    $listAdvertsTitles1 = array();
	    $listAdvertsIds1    = array();
	    $ids_AdvertsTitles  = array();


	    /////////////////////// get $ids_AdvertsTitles[]
	    //==== X last adverts Titles (X = 3)
	    $listAdvertsTitles = $em->getRepository('JOMANELPlatformBundle:Advert')
	                            ->getXLastAdvertsTitles($limite, $locale)
	    ;
	    //print_r($listAdvertsTitles);exit;

	    for ($i=0; $i < count($listAdvertsTitles); $i++) { 
	    	$listAdvertsTitles1[] = $listAdvertsTitles[$i]['title_'.$locale];
	    }
	    //print_r($listAdvertsTitles1);exit;

	    //==== X last adverts Ids (X = 3)
	    $listAdvertsIds    = $em->getRepository('JOMANELPlatformBundle:Advert')
	                            ->getXLastAdvertsIds($limite)
	    ;
	    //print_r($listAdvertsIds);exit;

	    for ($i=0; $i < count($listAdvertsIds); $i++) { 
	    	$listAdvertsIds1[] = $listAdvertsIds[$i]['id'];
	    }
	    //print_r($listAdvertsIds1);exit;

	    //==== $ids_AdvertsTitles[]
	    for ($i=0; $i < count($listAdvertsTitles1); $i++) { 
	    	$ids_AdvertsTitles[$listAdvertsIds1[$i]] = $listAdvertsTitles1[$i];
	    }
	    //print_r($ids_AdvertsTitles);exit;
	    ///////////////////////

	    if( count($ids_AdvertsTitles) == 0 ){
	    	$ids_AdvertsTitles = null;
	    }

	    return $this->render('JOMANELPlatformBundle:Advert:menu.html.twig', array('ids_AdvertsTitles' => $ids_AdvertsTitles));
    }//fnc


    
    /**
     * @Security("has_role('ROLE_USER') or has_role('ROLE_ADMIN')")
     */
    public function applyAction(Advert $advert, Request $request){
  
	    //=== find this advert by here id : 
	    $em = $this->getDoctrine()->getManager();

	    /*
	    // On récupère l'annonce $id
	    $advert = $em->getRepository('JOMANELPlatformBundle:Advert')->find($id);

	    if (null === $advert) {
	      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
	    }
	    */
	    
	    ////////////////////////////////////Data of User who apply////////////////////////////////////
	    $user         = $this->getUser();
	    $userUsername = $user->getUsername();
	    $userEmail    = $user->getEmail();
	    //echo $userUsername;exit;

	    $userIp = $request->getClientIp();
		if($userIp == 'unknown'){
		    $userIp = $_SERVER['REMOTE_ADDR'];
		}
	    //echo $userIp;exit;
	    //////////////////////////////////////////////////////////////////////////////////////////////
	    
	    //=== add application to this advert
	    //creating vew application 
	    $application = new Application();
        $application->setAuthor($userUsername);
        $application->setEmail($userEmail);
        $ipClient = $this->container->get('request_stack')->getCurrentRequest()->getClientIp();
        $application->setIP($userIp);
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


    public function purgeAction(){

	    // On récupère le service
	    $puger = $this->container->get('jomanel_platform.purger.advert');//.advert
	    $puger->purge(3); // purge les vielles de 3 jours et qui n'ont pas de candidature

	    //=== render
	    return $this->render('JOMANELPlatformBundle:Advert:purge.html.twig');
	    
    }//fnc


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


/*// On récupère la liste des candidatures de cette annonce
	    $listApplications = $em
	      ->getRepository('JOMANELPlatformBundle:Application')
	      ->findBy(array('advert' => $advert))
	    ;*/
