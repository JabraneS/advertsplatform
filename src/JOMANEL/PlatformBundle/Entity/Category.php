<?php

namespace JOMANEL\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="JOMANEL\PlatformBundle\Repository\CategoryRepository")
 * @UniqueEntity(fields="name_fr", message="une catégorie existe déja avec ce titre.")
 * @UniqueEntity(fields="name_en", message="a category already exists with this title.")
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="name_fr", type="string", length=255, unique=true)
     */
    private $name_fr;


    /**
     * @var string
     *
     * @ORM\Column(name="name_en", type="string", length=255, unique=true)
     */
    private $name_en;

    /**
     * @ORM\ManyToMany(targetEntity="JOMANEL\PlatformBundle\Entity\Advert", mappedBy="categories")
     */
    private $adverts; // Notez le « s », des catégories sont liée à plusieurs adverts



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

   
    /**
     * Set nameFr
     *
     * @param string $nameFr
     *
     * @return Category
     */
    public function setNameFr($nameFr)
    {
        $this->name_fr = $nameFr;

        return $this;
    }

    /**
     * Get nameFr
     *
     * @return string
     */
    public function getNameFr()
    {
        return $this->name_fr;
    }

    /**
     * Set nameEn
     *
     * @param string $nameEn
     *
     * @return Category
     */
    public function setNameEn($nameEn)
    {
        $this->name_en = $nameEn;

        return $this;
    }

    /**
     * Get nameEn
     *
     * @return string
     */
    public function getNameEn()
    {
        return $this->name_en;
    }
    ///////

    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->adverts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add advert
     *
     * @param \JOMANEL\PlatformBundle\Entity\Advert $advert
     *
     * @return Category
     */
    public function addAdvert(Advert $advert)
    {
        $this->adverts[] = $advert;

        // On lie l'annonce à la candidature
        //$advert->setCategory($this);

        return $this;
    }

    /**
     * Remove advert
     *
     * @param \JOMANEL\PlatformBundle\Entity\Advert $advert
     */
    public function removeAdvert(Advert $advert)
    {
        $this->adverts->removeElement($advert);

        //$this->applications->removeElement($application);

        // Et si notre relation était facultative (nullable=true, ce qui n'est pas notre cas ici attention) :        
        // $application->setAdvert(null);
    }

    /*public function removeAdverts(array $adverts)
    {
        $this->adverts->removeElement($advert);

        //$this->applications->removeElement($application);

        // Et si notre relation était facultative (nullable=true, ce qui n'est pas notre cas ici attention) :        
        // $application->setAdvert(null);
    }*/

    /**
     * Get adverts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdverts()
    {
        return $this->adverts;
    }
}
