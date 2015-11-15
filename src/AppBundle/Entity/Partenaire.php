<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Image;

/**
 * Partenaire
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PartenaireRepository")
 */
class Partenaire
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
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;


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
     * @return Partenaire
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
     * @return Partenaire
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
     * @return Partenaire
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
     * @return Partenaire
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
     * Set position
     *
     * @param integer $position
     * @return Partenaire
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }
}
