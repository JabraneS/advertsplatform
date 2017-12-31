<?php

namespace JOMANEL\CoreBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use JOMANEL\PlatformBundle\Email\Mailer;
use JOMANEL\CoreBundle\Entity\Contact;



class ContactListener{

  
  private $mailer;
  //private $antifloodValidator;

  public function __construct(Mailer $mailer){//ApplicationMailer $applicationMailer
    
    $this->mailer  = $mailer;
  }

  public function postPersist(LifecycleEventArgs $args){
    
    $entity = $args->getObject();

    // On ne veut envoyer un email que pour les entités Application
    if (!$entity instanceof Contact) {
      return;
    }
    //
    $nameVisitor    = $entity->getName();
    $emailVisitor   = $entity->getEmail();//exit;
    $messageVisitor = $entity->getMessage();//exit;
    
    $this->mailer->sendNewNotification_Contact($entity, $nameVisitor, $emailVisitor, $messageVisitor);

  }//fnc

  
}//class
