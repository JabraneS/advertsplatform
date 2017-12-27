<?php

namespace JOMANEL\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="JOMANEL\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser{

  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;
  

  public function __construct(){
    
    parent::__construct();
        
    // Add default role
    //$this->addRole("ROLE_USER");
        
  }//construct

}//class