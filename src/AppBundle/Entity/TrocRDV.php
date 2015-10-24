<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\TrocSection;
use AppBundle\Entity\AddressRdv;

/**
 * TrocRDV
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TrocRDVRepository")
 */
class TrocRDV
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
     * @ORM\Column(name="suggested", type="integer", nullable=true)
     */
    private $suggested;

    /**
     * @ORM\OneToMany(targetEntity="AddressRdv", mappedBy="trocRdv", cascade={"persist", "remove"})
     */
    protected $addressRdvs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="added_at", type="datetime")
     */
    private $addedAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="TrocSection", inversedBy="rdvs")
     * @ORM\JoinColumn(name="troc_section_id", referencedColumnName="id")
     */
    protected $trocSection;
    
    public function __toString() {
        return (string) $this->id;
    }
    
    public function __construct() {
        $this->addedAt = new \DateTime();
        $this->addressRdvs = new ArrayCollection();
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
     * Set suggested
     *
     * @param integer $suggested
     * @return TrocRDV
     */
    public function setSuggested($suggested)
    {
        $this->suggested = $suggested;

        return $this;
    }

    /**
     * Get suggested
     *
     * @return integer 
     */
    public function getSuggested()
    {
        return $this->suggested;
    }
    
    /**
     * Add addressRdvs
     *
     * @param \AppBundle\Entity\AddressRdv $addressRdvs
     * @return TrocRDV
     */
    public function addAddressRdv(\AppBundle\Entity\AddressRdv $addressRdvs) {
        $this->addressRdvs[] = $addressRdvs;

        return $this;
    }

    /**
     * Remove addressRdvs
     *
     * @param \AppBundle\Entity\AddressRdv $addressRdvs
     */
    public function removeAddressRdv(\AppBundle\Entity\AddressRdv $addressRdvs) {
        $this->addressRdvs->removeElement($addressRdvs);
    }

    /**
     * Get addressRdvs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAddressRdvs() {
        return $this->addressRdvs;
    }

    /**
     * Set addedAt
     *
     * @param \DateTime $addedAt
     * @return TrocRDV
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
     * Set trocSection
     *
     * @param \AppBundle\Entity\TrocSection $trocSection
     * @return TrocRDV
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
