<?php
/*
namespace JOMANEL\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use JOMANEL\PlatformBundle\Entity\Category;


class LoadCategory implements FixtureInterface{

  // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
  public function load(ObjectManager $manager){


      $categoriesNoms  = array("Génie Informatique", "Génie Mécanique", "Génie Civile", "Génie Electrique", "Génie Chimique");
      $categoriesNames = array("Computer Engineering", "Mechanical Engineering", "Civil Engineering", "Electrical Engineering", "Chemical Engineering");
      
      for ($i=0; $i < count($categoriesNoms); $i++) { 
        
        $category = new Category();
        $category->setNom($categoriesNoms[$i]);
        $category->setName($categoriesNames[$i]);
        
        // On la persiste
        $manager->persist($category);

      }//

    // On déclenche l'enregistrement de toutes les catégories
    $manager->flush();

  }//function

}//class

*/