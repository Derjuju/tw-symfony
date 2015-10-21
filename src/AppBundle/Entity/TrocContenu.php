<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var string
     *
     * @ORM\Column(name="bouteilles_a", type="string", length=255)
     */
    private $bouteillesA;
    
    /**
     * @var string
     *
     * @ORM\Column(name="bouteilles_b", type="string", length=255)
     */
    private $bouteillesB;
    
    /**
     * @ORM\ManyToOne(targetEntity="TrocSection", inversedBy="rdvs")
     * @ORM\JoinColumn(name="troc_section_id", referencedColumnName="id")
     */
    protected $trocSection;


    public function __toString() {
        return (string) $this->id;
    }
    
    public function __construct() {
        $this->addedAt = new \Symfony\Component\Validator\Constraints\DateTime();
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
     * Set bouteillesA
     *
     * @param string $bouteillesA
     * @return TrocContenu
     */
    public function setBouteillesA($bouteillesA)
    {
        $this->bouteillesA = $bouteillesA;

        return $this;
    }

    /**
     * Get bouteillesA
     *
     * @return string 
     */
    public function getBouteillesA()
    {
        return $this->bouteillesA;
    }
    
    /**
     * Set bouteillesB
     *
     * @param string $bouteillesB
     * @return TrocContenu
     */
    public function setBouteillesB($bouteillesB)
    {
        $this->bouteillesB = $bouteillesB;

        return $this;
    }

    /**
     * Get bouteillesB
     *
     * @return string 
     */
    public function getBouteillesB()
    {
        return $this->bouteillesB;
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
