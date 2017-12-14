<?php


namespace JOMANEL\PlatformBundle\EventListener;

use JOMANEL\PlatformBundle\Beta\BetaHTMLAdder;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;


class BetaListener
{
  // Notre processeur
  protected $betaHTML;

  // La date de fin de la version bêta :
  // - Avant cette date, on affichera un compte à rebours (J-3 par exemple)
  // - Après cette date, on n'affichera plus le « bêta »
  protected $endDate;

  public function __construct(BetaHTMLAdder $betaHTML, $endDate)
  {
    $this->betaHTML = $betaHTML;
    $this->endDate  = new \Datetime($endDate);
  }

  public function processBeta(FilterResponseEvent $event)
  {
    // On teste si la requête est bien la requête principale (et non une sous-requête)
    if (!$event->isMasterRequest()) {
      return;
    }

    //
    $remainingDays = $this->endDate->diff(new \Datetime())->days;
    
    if ($remainingDays <= 0) {
      // Si la date est dépassée, on ne fait rien
      return;
      //exit;
    }

    // On utilise notre BetaHRML
    $response = $this->betaHTML->addBeta($event->getResponse(), $remainingDays);
    
    // On met à jour la réponse avec la nouvelle valeur
    $event->setResponse($response);

  }
}

