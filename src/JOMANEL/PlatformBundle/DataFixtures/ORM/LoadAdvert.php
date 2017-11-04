<?php
/*
namespace JOMANEL\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JOMANEL\PlatformBundle\Entity\Advert;
use JOMANEL\PlatformBundle\Entity\Image;
use JOMANEL\PlatformBundle\Entity\Category;
use JOMANEL\PlatformBundle\Entity\Application;

class LoadAdvert implements FixtureInterface{

  // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
  public function load(ObjectManager $manager){

      $advertsTitles = array("Recherche développeur Symfony3.", "Recherche dév C++", "Recherche ingénieur en méca du fluide", "Recherche ingénieur génie civil", "Recherche ingénieur Electricien", "Recherche ingénieur en chimie");
      
      $advertsAuthors = array("joel", "joel", "maxim", "karim", "ahmed", "lilya");

      $emails = array("enarbajx@hotmail.com", "enarbajx@hotmail.com", "enarbajx@hotmail.com", "enarbajx@hotmail.com", "enarbajx@hotmail.com", "enarbajx@hotmail.com");

      $advertsContents = array("Motivé", "Sérieux", "Débrouillard", "Souriant", "Ponctuel", "Laborieux");

      $imagesUrls = array("https://img0bm.b8cdn.com/images/course/74/2048674_1489301535_35.jpg", "https://img0bm.b8cdn.com/images/course/74/2048674_1489301535_35.jpg", "https://me.ucsb.edu/sites/me.ucsb.edu/files/me_images/ME_LogoTransparent_Lowres-white.png", "http://www.mnit.ac.in/dept_civil/images/department.jpg", "http://zyzixun.net/data/out/55/3252930-electrical-engineering-wallpapers.jpg", "http://www.chemicalengineer.com/images/logo.png");

      $imagesAlts = array('img_dataprocessingField', 'img_dataprocessingField', 'img_mechanicalField', 'img_civilField','img_electricalField',  'img_chimicalField');

      $categoriesNames = array("Génie Informatique", "Génie Informatiquee", "Génie Mécanique", "Génie civile", "Génie Electrique", "Génie chimique");

      //$applicationAuthors  = array("étudiant1", "étudiant2", "étudiant3", "étudiant4", "étudiant5", "étudiant1");
      
      //$applicationContents = array("intéréssé et motivé par cet advert", "intéréssé et motivé par cet advert", "intéréssé et motivé par cet advert", "intéréssé et motivé par cet advert", "intéréssé et motivé par cet advert", "intéréssé et motivé par cet advert");

      
      for ($i=0; $i < count($advertsTitles); $i++) { 
        
        // Ajout de l'advert i 
        $advert = new Advert();
        $advert->setTitle($advertsTitles[$i]);
        $advert->setAuthor($advertsAuthors[$i]);
        $advert->setEmail($emails[$i]);
        $advert->setContent($advertsContents[$i]);

        // Mettre une image à cet Advert
        $image = new Image();
        $image->setUrl($imagesUrls[$i]);
        $image->setAlt($imagesAlts[$i]);

        // Mettre une category à cet Advert
        $category = new Category();
        $category->setName($categoriesNames[$i]);

        // Mettre une candidature à cet Advert
        //$application = new Application();
        //$application->setAuthor($applicationAuthors[$i]);
        //$application->setContent($applicationContents[$i]);

        //=> Relations :
        
        // On lie l'advert à l'image
        $advert->setImage($image);
        
        // On lie l'advert à la catégorie
        $advert->addCategory($category);

        // On lie l'advert à l'application
        //$advert->addApplication($application);
        
        // On la persiste

        $manager->persist($advert);
        //$manager->persist($application);

      }//

    // On déclenche l'enregistrement de toutes les catégories
    $manager->flush();

  }//function

}//class
*/