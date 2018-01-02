<?php

namespace JOMANEL\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RequestStack;

use JOMANEL\PlatformBundle\Entity\Category;
use JOMANEL\PlatformBundle\Form\CategoryType;
use JOMANEL\PlatformBundle\Form\CategoryEditType;

use JOMANEL\PlatformBundle\Entity\Post;
use JOMANEL\PlatformBundle\Form\PostType;


class CategoryController extends Controller{

	
	public function indexAction($page, Request $request){

	    $locale = $request->getLocale();
	    $em     = $this->getDoctrine()->getManager();

	    $nbPerPage = 4;

	    // Notre liste de categories en dur
	    $listCategories = $em->getRepository('JOMANELPlatformBundle:Category')
			    		     ->getAllCategoriesWithPaginator($page, $nbPerPage, $locale)
	    ;
	    //print_r($listCategories);exit;
	    
	    ////////
	    if ($locale == 'fr') {
	    	for ($i=0; $i < count($listCategories); $i++) { 
		    	unset($listCategories[$i]['name_en']);
		    }
	    }else{
	    	for ($i=0; $i < count($listCategories); $i++) { 
		    	unset($listCategories[$i]['name_fr']);
		    }
	    }
	    //print_r($listCategories);exit;
	    ////////

	    // On calcule le nombre total de pages grâce au count($listCategories) qui retourne le nombre total d'annonces
	    $nbPages = ceil(count($listCategories) / $nbPerPage);
	    //echo $nbPages;exit;

	    // Si la page n'existe pas, on retourne une 404
	    if ($page > $nbPages) {
	      //throw $this->createNotFoundException("La page ".$page." n'existe pas.");
	      $nbPages = null;
	    }

	    // On donne toutes les informations nécessaires à la vue
	    return $this->render('JOMANELPlatformBundle:Category:index.html.twig', array(
	      'listCategories' => $listCategories,
	      'nbPages'        => $nbPages,
	      'page'           => $page,
	    ));


    }//fnc

	
	public function viewAction(Request $request, $id){
		//echo "view";exit;
    	$locale = $request->getLocale();

    	$em = $this->getDoctrine()->getManager();

	    // On récupère la categorie $id
	    $arrayCategory = $em->getRepository('JOMANELPlatformBundle:Category')->getOneCategory($locale, $id);
	    //echo $id;exit;
	    //print_r($arrayCategory);exit;
	    if(count($arrayCategory) != 0){
	    	$category = $arrayCategory[0]['name_'.$locale];
	    	 //print_r($category);exit;
	    }
	    else{
	    	$category = null;
	    }
	   

	    return $this->render('JOMANELPlatformBundle:Category:view.html.twig', array(
	      'category'   => $category,
	      'categoryId' => $id
	    ));

    }//fnc


	public function addAction(Request $request){

    	$category = new Category();
	    $form   = $this->get('form.factory')->create(CategoryType::class, $category);

	    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
		    $em = $this->getDoctrine()->getManager();
		    //$ipClient = $this->container->get('request_stack')->getCurrentRequest()->getClientIp();
		    //$advert->setIP($ipClient);
		    $em->persist($category);
		    $em->flush();

		    $request->getSession()->getFlashBag()->add('notice', 'Categorie bien enregistrée.');

		    //return $this->redirectToRoute('jomanel_platform_addCategory', array('id' => $category->getId()));
		    return $this->redirectToRoute('jomanel_platform_viewCategory', array('id' => $category->getId()));
	    }

	    return $this->render('JOMANELPlatformBundle:Category:add.html.twig', array('form' => $form->createView()));
    }


    public function findAdvertsAction($page, Request $request){

    	$locale = $request->getLocale();
	    $em     = $this->getDoctrine()->getManager();

	    $nbPerPage = 4;

	    // Notre liste de categories en dur
	    $listCategories = $em->getRepository('JOMANELPlatformBundle:Category')
			    		     ->getAllCategoriesWithPaginator($page, $nbPerPage, $locale)
	    ;
	    //print_r($listCategories);exit;
	    
	    ////////
	    if ($locale == 'fr') {
	    	for ($i=0; $i < count($listCategories); $i++) { 
		    	unset($listCategories[$i]['name_en']);
		    }
	    }else{
	    	for ($i=0; $i < count($listCategories); $i++) { 
		    	unset($listCategories[$i]['name_fr']);
		    }
	    }
	    //print_r($listCategories);exit;
	    ////////

	    // On calcule le nombre total de pages grâce au count($listCategories) qui retourne le nombre total d'annonces
	    $nbPages = ceil(count($listCategories) / $nbPerPage);
	    //echo $nbPages;exit;

	    // Si la page n'existe pas, on retourne une 404
	    if ($page > $nbPages) {
	      //throw $this->createNotFoundException("La page ".$page." n'existe pas.");
	      $nbPages = null;
	    }

	    // On donne toutes les informations nécessaires à la vue
	    return $this->render('JOMANELPlatformBundle:Category:listCat.html.twig', array(
	      'listCategories' => $listCategories,
	      'nbPages'        => $nbPages,
	      'page'           => $page,
	    ));
    }//fnc


    public function viewListAdvsOfCategoryAction(Request $request, $id){

    	$locale = $request->getLocale();

    	$em = $this->getDoctrine()->getManager();

	    // On récupère la categorie $id
	    $category = $em->getRepository('JOMANELPlatformBundle:Category')->find($id);
	    
	    if(count($category->getAdverts()) != 0){
	    	$presenceAdvsInCat = true;
	    }
	    else{
	    	$presenceAdvsInCat = false;
	    }
	    //echo $presenceAdvsInCat;exit;
	    //print_r($category);exit;
	    return $this->render('JOMANELPlatformBundle:Category:viewCatAdvsList.html.twig', array(
	      'category'          => $category,
	      'presenceAdvsInCat' => $presenceAdvsInCat
	    ));

    }//fnc

    



    public function editAction($id, Request $request){
	    
	    $locale = $request->getLocale();
	    $em     = $this->getDoctrine()->getManager();


	    ////////
	    $categoryObject = $em->getRepository('JOMANELPlatformBundle:Category')->find($id);

	    /*if (null === $categoryObject) {
	      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
	    }*/

	    $form = $this->get('form.factory')->create(CategoryEditType::class, $categoryObject);

	    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
	      	// Inutile de persister ici, Doctrine connait déjà notre annonce
	      	$em->flush();

	      	$request->getSession()->getFlashBag()->add('notice', 'Category bien modifiée.');

	      	return $this->redirectToRoute('jomanel_platform_viewCategory', array('id' => $categoryObject->getId()));
	    }
	    //exit;
	    ////////

	    
		$arrayCategory = $em->getRepository('JOMANELPlatformBundle:Category')->getOneCategory($locale, $id);
	    //print_r($arrayCategory);exit;

	    if(count($arrayCategory) != 0){
	    	$category = $arrayCategory[0]['name_'.$locale];
	    	//print_r($category);exit;
	    }
	    else{
	    	$category = null;
	    }

	    

	    return $this->render('JOMANELPlatformBundle:Category:edit.html.twig', array(
	      	'category'   => $category,
	      	'form'       => $form->createView(),
	      	'categoryId' => $id
	    ));
	}//fnc


	public function deleteAction(Request $request, $id){

	    $locale = $request->getLocale();
	    $em     = $this->getDoctrine()->getManager();

	    $categoryObject = $em->getRepository('JOMANELPlatformBundle:Category')->find($id);

	    $advertsObjects = $categoryObject->getAdverts();
	    
	    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
	    // Cela permet de protéger la suppression d'annonce contre cette faille
	    $form = $this->get('form.factory')->create();

	    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
	      
	    	for($i=0; $i<count($advertsObjects); $i++){
	    		$em->remove($advertsObjects[$i]);
	    	}
	      
	      	$em->remove($categoryObject);
	      	$em->flush();
	     
		    $request->getSession()->getFlashBag()->add('info', "La catégorie a bien été supprimée.");
		      
		    return $this->redirectToRoute('jomanel_platform_homeCategory');
	    }

	    ///////////
	    $arrayCategory = $em->getRepository('JOMANELPlatformBundle:Category')->getOneCategory($locale, $id);
	    //print_r($arrayCategory);exit;

	    if(count($arrayCategory) != 0){
	    	$category = $arrayCategory[0]['name_'.$locale];
	    	//print_r($category);exit;
	    }
	    else{
	    	$category = null;
	    }
	    ///////////
	    
	    return $this->render('JOMANELPlatformBundle:Category:delete.html.twig', array(
	      'category'   => $category,
	      'categoryId' => $id,
	      'form'       => $form->createView(),
	    ));
  	}//fnc

}//class