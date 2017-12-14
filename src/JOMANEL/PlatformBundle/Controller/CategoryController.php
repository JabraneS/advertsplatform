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

	    $ids_listCategories_locale = array();
	    $em                        = $this->getDoctrine()->getManager();
	    $locale                    = $request->getLocale();

	    $listCategories_locale = $em->getRepository('JOMANELPlatformBundle:Category')
	                                ->getAllCategories($locale)
	    ;
	    //print_r($listCategories_locale);exit;

	    if(count($listCategories_locale) != 0){
	    	for ($i=0; $i <count($listCategories_locale) ; $i++) { 
	    		$listCategories_locale1[$i] = $listCategories_locale[$i]['name_'.$locale];
		    }
		    //print_r($listCategories_locale1);exit;

		    ///////////////
		    $categoryIds = $em->getRepository('JOMANELPlatformBundle:Category')
		                      ->getAllIdsCategories()
		    ;
		    //print_r($categoryIds);exit;

		    for ($i=0; $i <count($categoryIds) ; $i++) { 
	    		$categoryIds1[$i] = $categoryIds[$i]['id'];
		    }
		    //print_r($categoryIds1);exit;
		    ///////////////

		    //foreach ($listCategories_locale1 as $key => $value) {
		    for($i=0; $i<count($listCategories_locale1); $i++){
		    	$ids_listCategories_locale[$categoryIds1[$i]] = $listCategories_locale1[$i];
		    }
		    //print_r($ids_listCategories_locale);exit;
	    }//if

	    // On donne toutes les informations nécessaires à la vue
	    return $this->render('JOMANELPlatformBundle:Category:index.html.twig', array(
	      'ids_listCategories_locale' => $ids_listCategories_locale,
	    ));
    }//fnc

	
	public function viewAction(Request $request, $id){
		//echo "view";exit;
    	$locale = $request->getLocale();

    	$em = $this->getDoctrine()->getManager();

	    // On récupère l'annonce $id
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
	    $em = $this->getDoctrine()->getManager();

	    $categoryObject = $em->getRepository('JOMANELPlatformBundle:Category')->find($id);

	    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
	    // Cela permet de protéger la suppression d'annonce contre cette faille
	    $form = $this->get('form.factory')->create();

	    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
	      $em->remove($categoryObject);
	      $em->flush();
	      //echo "form";exit;
	      $request->getSession()->getFlashBag()->add('info', "La catégorie a bien été supprimée.");
	      //echo "form";exit;
	      return $this->redirectToRoute('jomanel_platform_homeCategory');
	      //echo "form";exit;
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