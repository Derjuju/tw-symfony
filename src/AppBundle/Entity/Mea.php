<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Image;

/**
 * Mea
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\MeaRepository")
 */
class Mea
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
     * @ORM\Column(name="name_fr", type="string", length=255)
     */
    private $nameFr;

    /**
     * @var string
     *
     * @ORM\Column(name="name_uk", type="string", length=255)
     */
    private $nameUk;

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
     * @var string
     *
     * @ORM\Column(name="reference_vin", type="string", length=255)
     */
    private $referenceVin;


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
     * Set nameFr
     *
     * @param string $nameFr
     * @return Mea
     */
    public function setNameFr($nameFr)
    {
        $this->nameFr = $nameFr;

        return $this;
    }

    /**
     * Get nameFr
     *
     * @return string 
     */
    public function getNameFr()
    {
        return $this->nameFr;
    }

    /**
     * Set nameUk
     *
     * @param string $nameUk
     * @return Mea
     */
    public function setNameUk($nameUk)
    {
        $this->nameUk = $nameUk;

        return $this;
    }

    /**
     * Get nameUk
     *
     * @return string 
     */
    public function getNameUk()
    {
        return $this->nameUk;
    }

    

    /**
     * Set photo
     *
     * @param \AppBundle\Entity\Image $photo
     * @return Mea
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
     * @return Mea
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

    /**
     * Set referenceVin
     *
     * @param string $referenceVin
     * @return Mea
     */
    public function setReferenceVin($referenceVin)
    {
        $this->referenceVin = $referenceVin;

        return $this;
    }

    /**
     * Get referenceVin
     *
     * @return string 
     */
    public function getReferenceVin()
    {
        return $this->referenceVin;
    }
}
