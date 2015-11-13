<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Image;

/**
 * Selection
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\SelectionRepository")
 */
class Selection
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
     * @ORM\Column(name="sous_titre_fr", type="string", length=255, nullable=true)
     */
    private $sousTitreFr;

    /**
     * @var string
     *
     * @ORM\Column(name="sous_titre_uk", type="string", length=255, nullable=true)
     */
    private $sousTitreUk;

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
     * @return Selection
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
     * @return Selection
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
     * Set sousTitreFr
     *
     * @param string $sousTitreFr
     * @return Selection
     */
    public function setSousTitreFr($sousTitreFr)
    {
        $this->sousTitreFr = $sousTitreFr;

        return $this;
    }

    /**
     * Get sousTitreFr
     *
     * @return string 
     */
    public function getSousTitreFr()
    {
        return $this->sousTitreFr;
    }

    /**
     * Set sousTitreUk
     *
     * @param string $sousTitreUk
     * @return Selection
     */
    public function setSousTitreUk($sousTitreUk)
    {
        $this->sousTitreUk = $sousTitreUk;

        return $this;
    }

    /**
     * Get sousTitreUk
     *
     * @return string 
     */
    public function getSousTitreUk()
    {
        return $this->sousTitreUk;
    }

    /**
     * Set lien
     *
     * @param string $lien
     * @return Selection
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
     * @return Selection
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
     * Set position
     *
     * @param integer $position
     * @return Selection
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
