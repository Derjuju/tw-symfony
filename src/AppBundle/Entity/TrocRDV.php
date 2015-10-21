<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="accepted", type="boolean", nullable=true)
     */
    private $accepted;

    
    /**
     * @ORM\OneToOne(targetEntity="AddressRdv", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     * */
    protected $address;

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
     * @return TrocRDV
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
     * Set address
     *
     * @param \AppBundle\Entity\AddressRdv $address
     * @return TrocRDV
     */
    public function setAddress(\AppBundle\Entity\AddressRdv $address = null) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \AppBundle\Entity\AddressRdv 
     */
    public function getAddress() {
        return $this->address;
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
