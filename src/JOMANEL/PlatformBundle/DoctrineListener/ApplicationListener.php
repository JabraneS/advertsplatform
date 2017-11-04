<?php

namespace JOMANEL\PlatformBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use JOMANEL\PlatformBundle\Email\Mailer;
use JOMANEL\PlatformBundle\Entity\Application;
//
//use JOMANEL\PlatformBundle\Validator\AntifloodValidator;

class ApplicationListener{//ApplicationCreationListener

  
  private $mailer;
  //private $antifloodValidator;

  public function __construct(Mailer $mailer){//ApplicationMailer $applicationMailer
    
    $this->mailer = $mailer;
    //$this->antifloodValidator = $antifloodValidator;
  }

  public function postPersist(LifecycleEventArgs $args){
    
    $entity = $args->getObject();

    // On ne veut envoyer un email que pour les entités Application
    if (!$entity instanceof Application) {
      return;
    }
    //
    //$this->antifloodValidator->validate($entity);//$value, Constraint $constraint)
    $this->mailer->sendNewNotification($entity);

  }//fnc

  
}//class
