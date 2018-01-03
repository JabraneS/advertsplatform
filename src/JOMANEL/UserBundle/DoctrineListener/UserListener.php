<?php

namespace JOMANEL\UserBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use JOMANEL\PlatformBundle\Email\Mailer;
use JOMANEL\UserBundle\Entity\User;



class UserListener{//ApplicationCreationListener

  
  private $mailer;
  //private $antifloodValidator;

  public function __construct(Mailer $mailer){//ApplicationMailer $applicationMailer
    
    $this->mailer  = $mailer;
    
  }

  public function prePersist(LifecycleEventArgs $args){
    
    $entity = $args->getObject();
    //$locale = $this->request->getLocale();

    // On ne veut envoyer un email que pour les entités User
    if (!$entity instanceof User) {
      return;
    }
    //
    $nameUserReg     = $entity->getUsername();
    $emailUserReg    = $entity->getEmail();//exit;
    $passWordUserReg = $entity->getPassword();//exit;
    //echo $nameUserReg;exit;
    //echo $passWordUserReg;exit;
    
    
    //$this->mailer->sendMsgRegistraionToNewUser($entity);

  }//fnc

  
}//class
