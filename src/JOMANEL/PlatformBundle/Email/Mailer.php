<?php


namespace JOMANEL\PlatformBundle\Email;

use JOMANEL\PlatformBundle\Entity\Application;

class Mailer{//ApplicationMailer
  
  /**
   * @var \Swift_Mailer
   */
  private $mailer;

  public function __construct(\Swift_Mailer $mailer)
  {
    $this->mailer = $mailer;
  }

  //===================== Entity : Application
  public function sendNewNotification(Application $application)
  {
    $message = new \Swift_Message(
      'Nouvelle candidature',
      'Vous avez reçu une nouvelle candidature.'
    );

    $message
      ->addTo("enarbajx@hotmail.com"/*$application->getAdvert()->getEmail()*/) // "enarbajx@hotmail.com"
      ->addFrom('jabrane.saidi89@gmail.com')//admin@votresite.com
    ;

    $this->mailer->send($message);
  }


  /*public function sendNewNotificationRemove()//Application $application
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
  */

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
}//class
