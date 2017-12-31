<?php


namespace JOMANEL\PlatformBundle\Entity;


use Doctrine\ORM\Mapping as ORM;



/**
 *@ORM\Entity(repositoryClass="JOMANEL\PlatformBundle\Repository\ApplicationRepository")
 *@ORM\HasLifecycleCallbacks()
 */
class Application
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
   * @ORM\Column(name="author", type="string", length=255)
   */
  private $author;

  /**
   * @ORM\Column(name="email", type="string", length=255)
   */
  private $email;

  /**
   * @ORM\Column(name="ip", type="string", length=255)
   */
  private $ip;// = $this->container->get('request')->getClientIp();

  /**
   * @ORM\Column(name="content", type="text")
   */
  private $content;

  /**
   * @ORM\Column(name="date", type="datetime")
   */
  private $date;
  
  /**
   * @ORM\ManyToOne(targetEntity="JOMANEL\PlatformBundle\Entity\Advert", inversedBy="applications")
   * @ORM\JoinColumn(nullable=false)
   */
  private $advert;


  public function __construct(){
  
    $this->date = new \Datetime();
    
    //

  }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set advert
     *
     * @param \JOMANEL\PlatformBundle\Entity\Advert $advert
     *
     * @return Application
     */
    public function setAdvert(\JOMANEL\PlatformBundle\Entity\Advert $advert)
    {
        $this->advert = $advert;

        return $this;
    }

    /**
     * Get advert
     *
     * @return \JOMANEL\PlatformBundle\Entity\Advert
     */
    public function getAdvert()
    {
        return $this->advert;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Application
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Application
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Application
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Application
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }


    /**
     * @ORM\PrePersist
     */
    public function increase()
    {
      $this->getAdvert()->increaseApplication();
    }

    /**/
     /* @ORM\PreRemove
     /*/
    public function decrease()
    {
      $this->getAdvert()->decreaseApplication();
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return Application
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
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
}
