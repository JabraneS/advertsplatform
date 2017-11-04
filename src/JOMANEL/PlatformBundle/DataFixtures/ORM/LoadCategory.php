<?php

namespace JOMANEL\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use JOMANEL\PlatformBundle\Entity\Category;


class LoadCategory implements FixtureInterface{

  // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
  public function load(ObjectManager $manager){


      $categoriesNames = array("Génie Informatiquee", "Génie Mécanique", "Génie civile", "Génie Electrique", "Génie chimique");

      
      for ($i=0; $i < count($categoriesNames); $i++) { 
        
        $category = new Category();
        $category->setName($categoriesNames[$i]);
        
        // On la persiste
        $manager->persist($category);

      }//

    // On déclenche l'enregistrement de toutes les catégories
    $manager->flush();

  }//function

}//class