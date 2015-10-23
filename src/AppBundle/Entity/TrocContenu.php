<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\TrocSection;

/**
 * TrocContenu
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TrocContenuRepository")
 */
class TrocContenu
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
     * @var boolean
     *
     * @ORM\Column(name="accepted", type="boolean", nullable=true)
     */
    private $accepted;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="added_at", type="datetime")
     */
    private $addedAt;
        
    /**
     * @ORM\OneToMany(targetEntity="TrocBouteille", mappedBy="trocContenuA", cascade={"persist", "remove"})
     */
    protected $trocABouteilles;
    
    /**
     * @ORM\OneToMany(targetEntity="TrocBouteille", mappedBy="trocContenuB", cascade={"persist", "remove"})
     */
    protected $trocBBouteilles;
    
    /**
     * @ORM\ManyToOne(targetEntity="TrocSection", inversedBy="contenu")
     * @ORM\JoinColumn(name="troc_section_id", referencedColumnName="id")
     */
    protected $trocSection;


    public function __toString() {
        return (string) $this->id;
    }
    
    public function __construct() {
        $this->addedAt = new \DateTime();
        $this->trocABouteilles = new ArrayCollection();
        $this->trocBBouteilles = new ArrayCollection();
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
     * Set accepted
     *
     * @param boolean $accepted
     * @return TrocContenu
     */
    public function setAccepted($accepted)
    {
        $this->accepted = $accepted;

        return $this;
    }

    /**
     * Get accepted
     *
     * @return boolean 
     */
    public function getAccepted()
    {
        return $this->accepted;
    }

    /**
     * Set addedAt
     *
     * @param \DateTime $addedAt
     * @return TrocContenu
     */
    public function setAddedAt($addedAt)
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    /**
     * Get addedAt
     *
     * @return \DateTime 
     */
    public function getAddedAt()
    {
        return $this->addedAt;
    }
    
    /**
     * Add trocABouteilles
     *
     * @param \AppBundle\Entity\TrocBouteille $trocABouteilles
     * @return TrocContenu
     */
    public function addTrocABouteille(\AppBundle\Entity\TrocBouteille $trocABouteilles) {
        $this->trocABouteilles[] = $trocABouteilles;

        return $this;
    }

    /**
     * Remove trocABouteilles
     *
     * @param \AppBundle\Entity\TrocBouteille $trocABouteilles
     */
    public function removeTrocABouteille(\AppBundle\Entity\TrocBouteille $trocABouteilles) {
        $this->trocABouteilles->removeElement($trocABouteilles);
    }

    /**
     * Get trocABouteilles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTrocABouteilles() {
        return $this->trocABouteilles;
    }
    
    /**
     * Add trocBBouteilles
     *
     * @param \AppBundle\Entity\TrocBouteille $trocBBouteilles
     * @return TrocContenu
     */
    public function addTrocBBouteille(\AppBundle\Entity\TrocBouteille $trocBBouteilles) {
        $this->trocBBouteilles[] = $trocBBouteilles;

        return $this;
    }

    /**
     * Remove trocBBouteilles
     *
     * @param \AppBundle\Entity\TrocBouteille $trocBBouteilles
     */
    public function removeTrocBBouteille(\AppBundle\Entity\TrocBouteille $trocBBouteilles) {
        $this->trocBBouteilles->removeElement($trocBBouteilles);
    }

    /**
     * Get trocBBouteilles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTrocBBouteilles() {
        return $this->trocBBouteilles;
    }
    
    /**
     * Set trocSection
     *
     * @param \AppBundle\Entity\TrocSection $trocSection
     * @return TrocContenu
     */
    public function setTrocSection(\AppBundle\Entity\TrocSection $trocSection = null)
    {
        $this->trocSection = $trocSection;

        return $this;
    }

    /**
     * Get trocSection
     *
     * @return \AppBundle\Entity\TrocSection 
     */
    public function getTrocSection()
    {
        return $this->trocSection;
    }
}
