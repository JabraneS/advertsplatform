<?php
/*
namespace JOMANEL\PlatformBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
//use JOMANEL\PlatformBundle\Purger\AdvertPurger;
use JOMANEL\PlatformBundle\Email\Mailer;
use JOMANEL\PlatformBundle\Entity\Advert;

class AdvertListener{

  
  private $mailer;

  public function __construct(Mailer $mailer){//ApplicationMailer $applicationMailer
    
    $this->mailer = $mailer;
  }

  public function postRemove(LifecycleEventArgs $args){
    
    $entity = $args->getObject();

    // On ne veut purger (les vielles entités de x days et qui n'ont pas d'apply) que les entités Advert
    if (!$entity instanceof Advert) {
      return;
    }

    //$this->advertPurger->purge($days);
    $this->mailer->sendNewNotificationRemoveAdvert();

  }//fnc

}//class
*/