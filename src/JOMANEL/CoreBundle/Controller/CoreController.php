<?php

namespace JOMANEL\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JOMANEL\CoreBundle\Entity\Contact;
use JOMANEL\CoreBundle\Form\ContactType;



class CoreController extends Controller{

    public function indexAction(Request $request){

        return $this->render('JOMANELCoreBundle:Core:index.html.twig');
    }//fnc

    

    public function contactAction(Request $request){
    
        $locale = $request->getLocale();
       
        //=== contact form ===//
        $contact = new Contact();
        $form    = $this->get('form.factory')->create(ContactType::class, $contact, array('locale' => $locale));

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Contact bien enregistrée.');

            //return $this->redirectToRoute('jomanel_core_homepage');
            return $this->render('JOMANELCoreBundle:Core:ContactSucces.html.twig');
        }

        return $this->render('JOMANELCoreBundle:Core:add.html.twig', array('form' => $form->createView()));
    }//fnc



}//class
