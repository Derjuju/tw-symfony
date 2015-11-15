<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Image;

/**
 * Presse
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PresseRepository")
 */
class Presse
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre_fr", type="string", length=255)
     */
    private $titreFr;

    /**
     * @var string
     *
     * @ORM\Column(name="titre_uk", type="string", length=255)
     */
    private $titreUk;

    /**
     * @var string
     *
     * @ORM\Column(name="lien", type="string", length=255)
     */
    private $lien;

    
    /**
     * @ORM\OneToOne(targetEntity="Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     * */
    private $photo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean", options={"default":0})
     */
    private $actif;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    public function __construct() {
        //$this->createdAt = new \Symfony\Component\Validator\Constraints\DateTime();
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
     * Set titreFr
     *
     * @param string $titreFr
     * @return Presse
     */
    public function setTitreFr($titreFr)
    {
        $this->titreFr = $titreFr;

        return $this;
    }

    /**
     * Get titreFr
     *
     * @return string 
     */
    public function getTitreFr()
    {
        return $this->titreFr;
    }

    /**
     * Set titreUk
     *
     * @param string $titreUk
     * @return Presse
     */
    public function setTitreUk($titreUk)
    {
        $this->titreUk = $titreUk;

        return $this;
    }

    /**
     * Get titreUk
     *
     * @return string 
     */
    public function getTitreUk()
    {
        return $this->titreUk;
    }

    /**
     * Set lien
     *
     * @param string $lien
     * @return Presse
     */
    public function setLien($lien)
    {
        $this->lien = $lien;

        return $this;
    }

    /**
     * Get lien
     *
     * @return string 
     */
    public function getLien()
    {
        return $this->lien;
    }

    /**
     * Set photo
     *
     * @param \AppBundle\Entity\Image $photo
     * @return Presse
     */
    public function setPhoto(\AppBundle\Entity\Image $photo = null) {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \AppBundle\Entity\Image 
     */
    public function getPhoto() {
        return $this->photo;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return Mea
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean 
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Bouteille
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
