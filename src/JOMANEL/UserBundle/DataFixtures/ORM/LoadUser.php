<?php
/*
namespace JOMANEL\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JOMANEL\UserBundle\Entity\User;

class LoadUser implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    
    //====== ADM
    $adm = 'Jabrane'; 

    // On crée l'utilisateur
      $user = new User;

      // Le nom d'utilisateur et le mot de passe sont identiques pour l'instant
      $user->setUsername($adm);
      $user->setPassword($adm);

      // On ne se sert pas du sel pour l'instant
      $user->setSalt('');
      // On définit uniquement le role ROLE_USER qui est le role de base
      $user->setRoles(array('ROLE_ADMIN'));

      // On le persiste
      $manager->persist($user);

    //======= USERS
    $listNames = array('Alexandre', 'Marine', 'Anna');

    foreach ($listNames as $name) {
      // On crée l'utilisateur
      $user = new User;

      // Le nom d'utilisateur et le mot de passe sont identiques pour l'instant
      $user->setUsername($name);
      $user->setPassword($name);

      // On ne se sert pas du sel pour l'instant
      $user->setSalt('');
      // On définit uniquement le role ROLE_USER qui est le role de base
      $user->setRoles(array('ROLE_USER'));

      // On le persiste
      $manager->persist($user);
    }

    //======= Enregistrement
    $manager->flush();

  }//fnc

}//class
*/