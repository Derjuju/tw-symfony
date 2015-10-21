<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use AppBundle\Entity\Troc;
use AppBundle\Entity\Member;
use AppBundle\Entity\TrocMessage;
use AppBundle\Entity\TrocContenu;
use AppBundle\Entity\TrocRDV;

/**
 * TrocSection
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TrocSectionRepository")
 */
class TrocSection
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
     * @ORM\OneToOne(targetEntity="TrocMessage", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="troc_message_id", referencedColumnName="id")
     * */
    protected $message;
    
    /**
     * @ORM\OneToOne(targetEntity="TrocContenu", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="troc_contenu_id", referencedColumnName="id")
     * */
    protected $contenu;
    
    /**
     * @ORM\OneToOne(targetEntity="TrocRDV", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="troc_rdv_id", referencedColumnName="id")
     * */
    protected $rdv;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="added_at", type="datetime")
     */
    private $addedAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="trocSections")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    protected $member;
    
    /**
     * @ORM\ManyToOne(targetEntity="Troc", inversedBy="trocSections")
     * @ORM\JoinColumn(name="troc_id", referencedColumnName="id")
     */
    protected $troc;

    
    
    public function __toString() {
        return (string) $this->id.' - '.$this->reference;
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
     * Set addedAt
     *
     * @param \DateTime $addedAt
     * @return TrocSection
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
     * Set message
     *
     * @param \AppBundle\Entity\TrocMessage $message
     * @return TrocSection
     */
    public function setMessage(\AppBundle\Entity\TrocMessage $message = null) {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return \AppBundle\Entity\TrocMessage 
     */
    public function getMessage() {
        return $this->message;
    }
    
    /**
     * Set contenu
     *
     * @param \AppBundle\Entity\TrocContenu $contenu
     * @return TrocSection
     */
    public function setContenu(\AppBundle\Entity\TrocContenu $contenu = null) {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return \AppBundle\Entity\TrocContenu 
     */
    public function getContenu() {
        return $this->contenu;
    }
    
    /**
     * Set rdv
     *
     * @param \AppBundle\Entity\TrocRDV $rdv
     * @return TrocSection
     */
    public function setRdv(\AppBundle\Entity\TrocRDV $rdv = null) {
        $this->rdv = $rdv;

        return $this;
    }

    /**
     * Get rdv
     *
     * @return \AppBundle\Entity\TrocRDV
     */
    public function getRdv() {
        return $this->rdv;
    }
    
    
    /**
     * Set member
     *
     * @param \AppBundle\Entity\Member $member
     * @return TrocSection
     */
    public function setMember(\AppBundle\Entity\Member $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \AppBundle\Entity\Member 
     */
    public function getMember()
    {
        return $this->member;
    }
    
    
    /**
     * Set troc
     *
     * @param \AppBundle\Entity\Troc $troc
     * @return TrocSection
     */
    public function setTroc(\AppBundle\Entity\Troc $troc = null)
    {
        $this->troc = $troc;

        return $this;
    }

    /**
     * Get troc
     *
     * @return \AppBundle\Entity\Troc 
     */
    public function getTroc()
    {
        return $this->troc;
    }
}
