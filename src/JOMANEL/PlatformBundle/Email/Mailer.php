<?php


namespace JOMANEL\PlatformBundle\Email;

use Symfony\Component\HttpFoundation\Request;

use JOMANEL\PlatformBundle\Entity\Application;

use JOMANEL\CoreBundle\Entity\Contact;

use JOMANEL\UserBundle\Entity\User;


class Mailer{//ApplicationMailer
  
  /**
   * @var \Swift_Mailer
   */
  private $mailer;
  protected $container;
  protected $user;

  public function __construct(\Swift_Mailer $mailer, $container)
  {
    $this->mailer    = $mailer;
    $this->container = $container;
  }

  //===================== Entity : Application
  public function sendNewNotification(Application $application)
  {
    $locale = $this->container->get('request_stack')->getCurrentRequest()->getLocale();
    //echo $locale;exit;

    $user         = $this->container->get('security.token_storage')->getToken()->getUser();
    $userUsername = $user->getUsername();
    $userEmail    = $user->getEmail();
    //echo $userUsername;exit;

    if($locale == "fr"){
      $title   = 'Nouvelle candidature';
      $msg_adm = 'Vous avez reçu une nouvelle candidature.';
      $msg_usr = 'Bonjour '.$userUsername.','."\n\n".'Vous venez d\'envoyer une nouvelle candidature.'."\n".'Nous traiterons votre demande dans les meilleurs délais.'."\n\n".'Cordialement,'."\n".'L\'administrateur.';
    }
    else{
      $title   = 'New application';
      $msg_adm = 'You have received a new application.';
      $msg_usr = 'Hi '.$userUsername.','."\n\n".'You have just sent a new application.'."\n".'We will process your request as soon as possible.'."\n\n".'Well cordially,'."\n".'The administrator.';
    }

    //=================== to admin ====================//
    $message_to_admin = new \Swift_Message($title, $msg_adm);

    $message_to_admin->addTo("enarbajx@hotmail.com") // "enarbajx@hotmail.com"
                     ->addFrom('jabrane.saidi89@gmail.com')//admin@votresite.com
    ;
    
    //=================== to user ====================//
    $message_to_user = new \Swift_Message($title, $msg_usr);

    $message_to_user->addTo($userEmail) // 
                    ->addFrom('jabrane.saidi89@gmail.com')//admin@votresite.com
    ;
    //

    $this->mailer->send($message_to_admin);
    $this->mailer->send($message_to_user);
  
  }//fnc

  public function sendNewNotification_Contact(Contact $contact, $nameVisitor, $emailVisitor, $messageVisitor){

    $locale = $this->container->get('request_stack')->getCurrentRequest()->getLocale();
    //$userEmail = ;

    if($locale == "fr"){
      $title   = "Plateforme d'annonces : Contactez-nous";
      $msg_adm = "Vous avez reçu un nouveau message de la part d'un visiteur.";
      $msg_vtr = 'Bonjour '.$nameVisitor.','."\n\n".'Nous avons bien reçu votre message : ( '.$messageVisitor.' ).'."\n\n".'Cordialement,'."\n".'L\'administrateur.';
    }
    else{
      $title   = "Adverts Platform : Contact us";
      $msg_adm = "You have received a new message from a visitor.";
      $msg_vtr = 'Hi '.$nameVisitor.','."\n\n".'We have received your message: ( '.$messageVisitor.' ).'."\n\n".'Cordially,'."\n".'The administrator.';
    }

    //=================== to admin ====================//
    $message_to_admin = new \Swift_Message($title, $msg_adm);

    $message_to_admin->addTo("enarbajx@hotmail.com") // "enarbajx@hotmail.com"
                     ->addFrom('jabrane.saidi89@gmail.com')//admin@votresite.com
    ;
    
    //=================== to user ====================//
    $message_to_visitor = new \Swift_Message($title, $msg_vtr);

    $message_to_visitor->addTo($emailVisitor) // 
                       ->addFrom('jabrane.saidi89@gmail.com')//admin@votresite.com
    ;
    //

    $this->mailer->send($message_to_admin);
    $this->mailer->send($message_to_visitor);

  }//fnc


  public function sendMsgRegistraionToNewUser(User $userReg){

    $locale = $this->container->get('request_stack')->getCurrentRequest()->getLocale();
    //echo $locale;exit;

    $user         = $this->container->get('security.token_storage')->getToken()->getUser();
    $userUsername = $user->getUsername();
    $userEmail    = $user->getEmail();
    //echo $userUsername;exit;

  }//fnc

}//class

















/*
public function sendNewNotificationRemove()//Application $application
  {
    $message = new \Swift_Message(
      'supp candidature',
      'on a supp candidature.'
    );

    $message
      ->addTo("enarbajx@hotmail.com") // "enarbajx@hotmail.com"
      ->addFrom('jabrane.saidi89@gmail.com')//admin@votresite.com
    ;

    $this->mailer->send($message);
  }
  

  //===================== Entity : Advert
  public function sendNewNotificationRemoveAdvert(){

    $message = new \Swift_Message(
      'supp advert',
      'on a supp advert.'
    );

    $message
      ->addTo("enarbajx@hotmail.com") // "enarbajx@hotmail.com"
      ->addFrom('jabrane.saidi89@gmail.com')//admin@votresite.com
    ;

    $this->mailer->send($message);

  }
*/