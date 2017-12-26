<?php

namespace JOMANEL\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JOMANEL\PlatformBundle\Repository\AdvertRepository;


class CoreController extends Controller{

    public function indexAction(Request $request){

        return $this->render('JOMANELCoreBundle:Core:index.html.twig');
    }//fnc

    public function contactAction(Request $request){
    
        // Create the form according to the FormType created previously.
        // And give the proper parameters
        $form = $this->createForm('JOMANEL\CoreBundle\Form\ContactType',null,array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('jomanel_core_contactpage'),
            'method' => 'POST'
        ));

        if ($request->isMethod('POST')) {
            // Refill the fields in case the form is not valid.
            $form->handleRequest($request);

            if($form->isValid()){
                // Send mail
                if($this->sendEmail($form->getData())){

                    // Everything OK, redirect to wherever you want ! :
                    
                    return $this->render('JOMANELCoreBundle:Core:contactSucces.html.twig');//
                }else{
                    // An error ocurred, handle
                    var_dump("Errooooor :(");
                }
            }
        }

        return $this->render('JOMANELCoreBundle:Core:contact.html.twig', array(
            'form' => $form->createView()
        ));
    }//fnc


    private function sendEmail($data){
        
        $myappContactMail     = 'jabrane.saidi89@gmail.com';
        $myappContactPassword = 'googlejabrane5';
        
        // In this case we'll use the ZOHO mail services.
        // If your service is another, then read the following article to know which smpt code to use and which port
        // http://ourcodeworld.com/articles/read/14/swiftmailer-send-mails-from-php-easily-and-effortlessly
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465,'ssl')
            ->setUsername($myappContactMail)
            ->setPassword($myappContactPassword);

        $mailer = \Swift_Mailer::newInstance($transport);
        
        $message = \Swift_Message::newInstance("Our Code World Contact Form ". $data["subject"])
        ->setFrom(array($myappContactMail => "Message by ".$data["name"]))
        ->setTo(array(
            $myappContactMail => $myappContactMail
        ))
        ->setBody($data["message"]."<br>ContactMail :".$data["email"]);
        
        return $mailer->send($message);
    }//fnc


    public function selectLangAction($langue = null, GetResponseEvent $event)
    {
        //echo "string";exit();
        if($langue != null)
        {
            $request = $event->getRequest();
            $request->setLocale($langue/*$locale*/);
            //$this->container->get('request')->setLocale($langue);
        }
     
        $url = $this->container->get('request')->headers->get('referer');
        if(empty($url)) {
            $url = $this->container->get('router')->generate('home');
        }
        return new RedirectResponse($url);
    }


}//class
