<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Bouteille;

/**
 * TypePays
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TypePaysRepository")
 */
class TypePays
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
     * @ORM\Column(name="name_fr", type="string", length=255, nullable=true)
     */
    private $nameFr;

    /**
     * @var string
     *
     * @ORM\Column(name="name_uk", type="string", length=255, nullable=true)
     */
    private $nameUk;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255, nullable=true)
     */
    private $reference;

    
    /**
     * @ORM\OneToMany(targetEntity="Bouteille", mappedBy="typePays", cascade={"persist", "remove"})
     */
    protected $bouteilles;


    public function __construct() {
        $this->bouteilles = new ArrayCollection();
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
     * Set nameFr
     *
     * @param string $nameFr
     * @return TypePays
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
     * @return TypePays
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
     * Set reference
     *
     * @param string $reference
     * @return TypePays
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string 
     */
    public function getReference()
    {
        return $this->reference;
    }
    
    /**
     * Add bouteilles
     *
     * @param \AppBundle\Entity\Bouteille $bouteilles
     * @return TypePays
     */
    public function addBouteille(\AppBundle\Entity\Bouteille $bouteilles) {
        $this->bouteilles[] = $bouteilles;

        return $this;
    }

    /**
     * Remove bouteilles
     *
     * @param \AppBundle\Entity\Bouteille $bouteilles
     */
    public function removeBouteille(\AppBundle\Entity\Bouteille $bouteilles) {
        $this->bouteilles->removeElement($bouteilles);
    }

    /**
     * Get bouteilles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBouteilles() {
        return $this->bouteilles;
    }
}
