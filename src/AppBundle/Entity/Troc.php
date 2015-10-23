<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\Member;

/**
 * Troc
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TrocRepository")
 */
class Troc
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="finished_a", type="boolean")
     */
    private $finishedA;

    /**
     * @var boolean
     *
     * @ORM\Column(name="finished_b", type="boolean")
     */
    private $finishedB;

    /**
     * @var boolean
     *
     * @ORM\Column(name="add_to_cave_a", type="boolean", nullable=true)
     */
    private $addToCaveA;

    /**
     * @var boolean
     *
     * @ORM\Column(name="add_to_cave_b", type="boolean", nullable=true)
     */
    private $addToCaveB;

    /**
     * @var integer
     *
     * @ORM\Column(name="note_a", type="integer", nullable=true)
     */
    private $noteA;

    /**
     * @var integer
     *
     * @ORM\Column(name="note_b", type="integer", nullable=true)
     */
    private $noteB;

    /**
     * @var boolean
     *
     * @ORM\Column(name="archived", type="boolean")
     */
    private $archived;
    
    /**
     * @var string
     *
     * @ORM\Column(name="bouteilles_a", type="string", length=255, nullable=true)
     */
    private $bouteillesA;
    
    /**
     * @var string
     *
     * @ORM\Column(name="bouteilles_b", type="string", length=255, nullable=true)
     */
    private $bouteillesB;
    
    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="trocsA")
     * @ORM\JoinColumn(name="member_a_id", referencedColumnName="id")
     */
    protected $memberA;
    
    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="trocsB")
     * @ORM\JoinColumn(name="member_b_id", referencedColumnName="id")
     */
    protected $memberB;

    /**
     * @ORM\OneToMany(targetEntity="TrocSection", mappedBy="troc", cascade={"persist", "remove"})
     */
    protected $trocSections;
    
    
    
    public function __toString() {
        return (string) $this->id;
    }
    
    public function __construct() {
        $this->createdAt = new \DateTime();
        $this->finishedA = false;
        $this->finishedB = false;
        $this->addToCaveA = false;
        $this->addToCaveB = false;
        $this->archived = false;
        $this->trocSections = new ArrayCollection();
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Troc
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set finishedA
     *
     * @param boolean $finishedA
     * @return Troc
     */
    public function setFinishedA($finishedA)
    {
        $this->finishedA = $finishedA;

        return $this;
    }

    /**
     * Get finishedA
     *
     * @return boolean 
     */
    public function getFinishedA()
    {
        return $this->finishedA;
    }

    /**
     * Set finishedB
     *
     * @param boolean $finishedB
     * @return Troc
     */
    public function setFinishedB($finishedB)
    {
        $this->finishedB = $finishedB;

        return $this;
    }

    /**
     * Get finishedB
     *
     * @return boolean 
     */
    public function getFinishedB()
    {
        return $this->finishedB;
    }

    /**
     * Set addToCaveA
     *
     * @param boolean $addToCaveA
     * @return Troc
     */
    public function setAddToCaveA($addToCaveA)
    {
        $this->addToCaveA = $addToCaveA;

        return $this;
    }

    /**
     * Get addToCaveA
     *
     * @return boolean 
     */
    public function getAddToCaveA()
    {
        return $this->addToCaveA;
    }

    /**
     * Set addToCaveB
     *
     * @param boolean $addToCaveB
     * @return Troc
     */
    public function setAddToCaveB($addToCaveB)
    {
        $this->addToCaveB = $addToCaveB;

        return $this;
    }

    /**
     * Get addToCaveB
     *
     * @return boolean 
     */
    public function getAddToCaveB()
    {
        return $this->addToCaveB;
    }

    /**
     * Set noteA
     *
     * @param integer $noteA
     * @return Troc
     */
    public function setNoteA($noteA)
    {
        $this->noteA = $noteA;

        return $this;
    }

    /**
     * Get noteA
     *
     * @return integer 
     */
    public function getNoteA()
    {
        return $this->noteA;
    }

    /**
     * Set noteB
     *
     * @param integer $noteB
     * @return Troc
     */
    public function setNoteB($noteB)
    {
        $this->noteB = $noteB;

        return $this;
    }

    /**
     * Get noteB
     *
     * @return integer 
     */
    public function getNoteB()
    {
        return $this->noteB;
    }

    /**
     * Set archived
     *
     * @param boolean $archived
     * @return Troc
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * Get archived
     *
     * @return boolean 
     */
    public function getArchived()
    {
        return $this->archived;
    }
    
    
    
    /**
     * Set bouteillesA
     *
     * @param string $bouteillesA
     * @return Troc
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
     * @return Troc
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
     * Add trocSections
     *
     * @param \AppBundle\Entity\TrocSection $trocSections
     * @return Troc
     */
    public function addTrocSection(\AppBundle\Entity\TrocSection $trocSections) {
        $this->trocSections[] = $trocSections;

        return $this;
    }

    /**
     * Remove trocSections
     *
     * @param \AppBundle\Entity\TrocSection $trocSections
     */
    public function removeTrocSection(\AppBundle\Entity\TrocSection $trocSections) {
        $this->trocSections->removeElement($trocSections);
    }

    /**
     * Get trocSections
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTrocSections() {
        return $this->trocSections;
    }
    
    /**
     * Set memberA
     *
     * @param \AppBundle\Entity\Member $memberA
     * @return Troc
     */
    public function setMemberA(\AppBundle\Entity\Member $memberA = null)
    {
        $this->memberA = $memberA;

        return $this;
    }

    /**
     * Get memberA
     *
     * @return \AppBundle\Entity\Member 
     */
    public function getMemberA()
    {
        return $this->memberA;
    }
    
    
    /**
     * Set memberB
     *
     * @param \AppBundle\Entity\Member $memberB
     * @return Troc
     */
    public function setMemberB(\AppBundle\Entity\Member $memberB = null)
    {
        $this->memberB = $memberB;

        return $this;
    }

    /**
     * Get memberB
     *
     * @return \AppBundle\Entity\Member 
     */
    public function getMemberB()
    {
        return $this->memberB;
    }
}
