<?php
namespace JOMANEL\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


//
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RequestStack;
//




/**
 * @ORM\Table(name="advert")
 * @ORM\Entity(repositoryClass="JOMANEL\PlatformBundle\Repository\AdvertRepository")
 * @UniqueEntity(fields="title_fr", message="une annonce existe déja avec ce titre.")
 * @UniqueEntity(fields="title_en", message="An advert already exists with this title.")
 * @ORM\HasLifecycleCallbacks()
 */
class Advert 
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
   * @var \DateTime
   *
   * @ORM\Column(name="date", type="datetime")
   * @Assert\DateTime()
   */
  private $date;
  
  /**
   * @var string
   *
   * @ORM\Column(name="title_fr", type="string", length=255, unique=true)
   * @Assert\Length(min=10, max=50)
   */
  private $title_fr;

  /**
   * @var string
   *
   * @ORM\Column(name="title_en", type="string", length=255, unique=true)
   * @Assert\Length(min=10, max=50)
   */
  private $title_en;
  
  /**
   * @var string
   *
   * @ORM\Column(name="author", type="string", length=255)
   * @Assert\Length(min=2, max=30)
   */
  private $author;

  /**
   * @ORM\Column(name="ip", type="string", length=255)
   */
  private $ip;

  /**
   * @var string
   *
   * @ORM\Column(name="email", type="string", length=255)
   * @Assert\Email(checkMX=true)
   */
  private $email;
  
  /**
   * @var string
   *
   * @ORM\Column(name="contenu_fr", type="string", length=255)
   * @Assert\NotBlank()
   * @Assert\Length(min=10, max=600)
   */
  private $contenu_fr;

  /**
   * @var string
   *
   * @ORM\Column(name="contenu_en", type="string", length=255)
   * @Assert\NotBlank()
   * @Assert\Length(min=10, max=600)
   */
  private $contenu_en;


  
  /**
   * @ORM\Column(name="published", type="boolean")
   */
  private $published = true;
  
  /**
   * @ORM\OneToOne(targetEntity="JOMANEL\PlatformBundle\Entity\Image", cascade={"persist", "remove"})
   * @Assert\Valid()
   */
  private $image;
  
  /**
   * @ORM\ManyToMany(targetEntity="JOMANEL\PlatformBundle\Entity\Category", inversedBy="adverts")
   * @ORM\JoinTable(name="advert_category")
   */
  private $categories;
  
  /**
   * @ORM\OneToMany(targetEntity="JOMANEL\PlatformBundle\Entity\Application", mappedBy="advert")
   */
  private $applications; // Notez le « s », une annonce est liée à plusieurs candidatures

  /**
   * @ORM\Column(name="nb_applications", type="integer")
   */
  private $nbApplications = 0;
  
  /**
   * @ORM\Column(name="updated_at", type="datetime", nullable=true)
   */
  private $updatedAt;
  
  /**
   * @Gedmo\Slug(fields={"title_fr"})
   * @ORM\Column(name="slug_fr", type="string", length=255, unique=true)
   */
  private $slug_fr;

  /**
   * @Gedmo\Slug(fields={"title_en"})
   * @ORM\Column(name="slug_en", type="string", length=255, unique=true)
   */
  private $slug_en;



  public function __construct()
  {
    $this->date         = new \Datetime();
    //$this->ip           = //$this->request->getClientIp();//$this->get('request_stack')->getCurrentRequest()->getClientIp();
    $this->categories   = new ArrayCollection();
    $this->applications = new ArrayCollection();
  }

  /**
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param \DateTime $date
   */
  public function setDate($date)
  {
    $this->date = $date;
  }
  /**
   * @return \DateTime
   */
  public function getDate()
  {
    return $this->date;
  }

  /**
   * Set titleFr
   *
   * @param string $titleFr
   *
   * @return Advert
   */
  public function setTitleFr($titleFr)
  {
    $this->title_fr = $titleFr;

    return $this;
  }
  /**
   * Get titleFr
   *
   * @return string
   */
  public function getTitleFr()
  {
    return $this->title_fr;
  }

  /**
   * Set titleEn
   *
   * @param string $titleEn
   *
   * @return Advert
   */
  public function setTitleEn($titleEn)
  {
    $this->title_en = $titleEn;

    return $this;
  }
  /**
   * Get titleEn
   *
   * @return string
   */
  public function getTitleEn()
  {
    return $this->title_en;
  }

  /**
   * @param string $author
   */
  public function setAuthor($author)
  {
    $this->author = $author;
  }
  /**
   * @return string
   */
  public function getAuthor()
  {
    return $this->author;
  }

  /**
   * Get ip
   *
   * @return string
   */
  public function getIp()
  {
        return $this->ip;
  }
  /**
   * Set ip
   *
   * @param string $ip
   *
   * @return Advert
   */
  public function setIp($ip)
  {
    $this->ip = $ip;

    return $this;
  }

  public function setEmail($email)
  {
    $this->email = $email;
  }
  /**
   * @return string
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * Set contenuFr
   *
   * @param string $contenuFr
   *
   * @return Advert
   */
  public function setContenuFr($contenuFr)
  {
    $this->contenu_fr = $contenuFr;

    return $this;
  }
  /**
   * Get contenuFr
   *
   * @return string
   */
  public function getContenuFr()
  {
    return $this->contenu_fr;
  }

  /**
   * Set contenuEn
   *
   * @param string $contenuEn
   *
   * @return Advert
   */
  public function setContenuEn($contenuEn)
  {
    $this->contenu_en = $contenuEn;

    return $this;
  }
  /**
   * Get contenuEn
   *
   * @return string
   */
  public function getContenuEn()
  {
    return $this->contenu_en;
  }

  /**
   * @param bool $published
   */
  public function setPublished($published)
  {
    $this->published = $published;
  }
  /**
   * @return bool
   */
  public function getPublished()
  {
    return $this->published;
  }

  public function setImage(Image $image = null)
  {
    $this->image = $image;
  }
  public function getImage()
  {
    return $this->image;
  }

  /**
   * @param Category $category
   */
  public function addCategory(Category $category)
  {
    $this->categories[] = $category;
  }
  /**
   * @param Category $category
   */
  public function removeCategory(Category $category)
  {
    $this->categories->removeElement($category);
  }
  /**
   * @return ArrayCollection
   */
  public function getCategories()
  {
    return $this->categories;
  }

  /**
   * @param Application $application
   */
  public function addApplication(Application $application)
  {
    $this->applications[] = $application;
    // On lie l'annonce à la candidature
    $application->setAdvert($this);
  }
  /**
   * @param Application $application
   */
  public function removeApplication(Application $application)
  {
    $this->applications->removeElement($application);
  }
  /**
   * @return \Doctrine\Common\Collections\Collection
   */
  public function getApplications()
  {
    return $this->applications;
  }

  /**
   * Set nbApplications
   *
   * @param integer $nbApplications
   *
   * @return Advert
   */
  public function setNbApplications($nbApplications)
  {
    $this->nbApplications = $nbApplications;

    return $this;
  }
  /**
   * Get nbApplications
   *
   * @return integer
   */
  public function getNbApplications()
  {
    return $this->nbApplications;
  }

  /**
   * @param \DateTime $updatedAt
   */
  public function setUpdatedAt(\Datetime $updatedAt = null)
  {
      $this->updatedAt = $updatedAt;
  }
  /**
   * @return \DateTime
   */
  public function getUpdatedAt()
  {
      return $this->updatedAt;
  }

  /**
   * Set slugFr
   *
   * @param string $slugFr
   *
   * @return Advert
   */
  public function setSlugFr($slugFr)
  {
    $this->slug_fr = $slugFr;

    return $this;
  }
  /**
   * Get slugFr
   *
   * @return string
   */
  public function getSlugFr()
  {
    return $this->slug_fr;
  }

  /**
   * Set slugEn
   *
   * @param string $slugEn
   *
   * @return Advert
   */
  public function setSlugEn($slugEn)
  {
    $this->slug_en = $slugEn;

    return $this;
  }
  /**
   * Get slugEn
   *
   * @return string
   */
  public function getSlugEn()
  {
    return $this->slug_en;
  }


///////////////////////////////////////////////////////
  /**
   * @ORM\PreUpdate
   */
  public function updateDate()
  {
    $this->setUpdatedAt(new \Datetime());
  }
  public function increaseApplication()
  {
    $this->nbApplications++;
  }
  public function decreaseApplication()
  {
    $this->nbApplications--;
  }
///////////////////////////////////////////////////////
  ///**
  // * @Assert\Callback
  // */
  /*public function isContentValid(ExecutionContextInterface $context){

    if ($locale == "fr") {
      # code...
    }
    else{

    }
    $forbiddenWords = array('demotivation', 'abandonment', 'apathetic');

    // On vérifie que le contenu ne contient pas l'un des mots
    if (preg_match('#'.implode('|', $forbiddenWords).'#', $this->getContent())) {
      // La règle est violée, on définit l'erreur
      $context
        ->buildViolation('Invalid content because it contains a forbidden word.') // message
        ->atPath('content')                                                   // attribut de l'objet qui est violé
        ->addViolation() // ceci déclenche l'erreur, ne l'oubliez pas
      ;
    }
  }//fnc
  */
    
}//class
