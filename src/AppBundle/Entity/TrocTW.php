<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\TrocSection;
use AppBundle\Entity\AddressTW;

/**
 * TrocTW
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TrocTWRepository")
 */
class TrocTW
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
     * @ORM\ManyToOne(targetEntity="AddressTW")
     * @ORM\JoinColumn(nullable=false)
     * */
    protected $addressTW;

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
     * Set suggested
     *
     * @param integer $suggested
     * @return TrocTW
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
     * Set addressTW
     *
     * @param \AppBundle\Entity\AddressTW $addressTW
     * @return TrocTW
     */
    public function setAddressTW($addressTW) {
        $this->addressTW = $addressTW;

        return $this;
    }

    /**
     * Get addressTW
     *
     * @return \AppBundle\Entity\AddressTW
     */
    public function getAddressTW() {
        return $this->addressTW;
    }

    /**
     * Set addedAt
     *
     * @param \DateTime $addedAt
     * @return TrocTW
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
     * @return TrocTW
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
