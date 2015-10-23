<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Address;
use AppBundle\Entity\ExpertLevel;
use AppBundle\Entity\Image;
use AppBundle\Entity\Bouteille;
use AppBundle\Entity\Troc;
use AppBundle\Entity\TrocSection;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Util\SecureRandomInterface;

/**
 * Member
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\MemberRepository")
 */
class Member  implements UserInterface
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
     * @ORM\Column(name="login", type="string", length=255)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="confirmed_email", type="boolean", nullable=true, options={"default":0})
     */
    private $confirmedEmail;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean", options={"default":0})
     */
    private $actif;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_connexion", type="datetime", nullable=true)
     */
    private $lastConnexion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true, options={"default":0})
     */
    private $enabled;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="datetime")
     */
    private $birthdate;

    /**
     * @var string
     *
     * @ORM\Column(name="telephon", type="string", length=255, nullable=true)
     */
    private $telephon;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=255, nullable=true)
     */
    private $mobile;

    
    /**
     * @ORM\OneToOne(targetEntity="Address", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     * */
    protected $address;

    /**
     * @ORM\ManyToOne(targetEntity="ExpertLevel", inversedBy="members")
     * @ORM\JoinColumn(name="expert_level_id", referencedColumnName="id")
     */
    protected $expertLevel;

    /**
     * @ORM\OneToOne(targetEntity="Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     * */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity="Bouteille", mappedBy="member", cascade={"persist", "remove"})
     */
    protected $bouteilles;

    /**
     * @ORM\OneToMany(targetEntity="Troc", mappedBy="memberA", cascade={"persist", "remove"})
     */
    protected $trocsA;

    /**
     * @ORM\OneToMany(targetEntity="Troc", mappedBy="memberB", cascade={"persist", "remove"})
     */
    protected $trocsB;

    /**
     * @ORM\OneToMany(targetEntity="TrocSection", mappedBy="member", cascade={"persist", "remove"})
     */
    protected $trocSections;

    /**
     * @var integer
     *
     * @ORM\Column(name="note", type="integer", nullable=true)
     */
    private $note;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_troc", type="integer", nullable=true)
     */
    private $totalTroc;
    

    

    public function __toString() {
        return (string) $this->id.' - '.$this->login;
    }
    
    public function __construct() {
        $this->bouteilles = new ArrayCollection();
        $this->trocsA = new ArrayCollection();
        $this->trocsB = new ArrayCollection();
        $this->trocSections = new ArrayCollection();
        $this->note = 0;
        $this->totalTroc = 0;
    }

    public function getAvatarFile() {
        if ($this->avatar)
            return $this->getAvatar()->getFile();
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
     * Set login
     *
     * @param string $login
     * @return Member
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Member
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Member
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    public function getRoles() {

        return ['ROLE_USER'];
    }

    public function eraseCredentials() {
        
    }

    public function getUsername() {
        return $this->login;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Member
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set confirmedEmail
     *
     * @param boolean $confirmedEmail
     * @return Member
     */
    public function setConfirmedEmail($confirmedEmail) {
        $this->confirmedEmail = $confirmedEmail;

        return $this;
    }

    /**
     * Is confirmedEmail
     *
     * @return boolean 
     */
    public function isConfirmedEmail() {
        return $this->confirmedEmail;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return Member
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Is actif
     *
     * @return boolean 
     */
    public function isActif()
    {
        return $this->actif;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Member
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
     * Set lastConnexion
     *
     * @param \DateTime $lastConnexion
     * @return Member
     */
    public function setLastConnexion($lastConnexion)
    {
        $this->lastConnexion = $lastConnexion;

        return $this;
    }

    /**
     * Get lastConnexion
     *
     * @return \DateTime 
     */
    public function getLastConnexion()
    {
        return $this->lastConnexion;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Member
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Is enabled
     *
     * @return boolean 
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Member
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Member
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return Member
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime 
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set telephon
     *
     * @param string $telephon
     * @return Member
     */
    public function setTelephon($telephon)
    {
        $this->telephon = $telephon;

        return $this;
    }

    /**
     * Get telephon
     *
     * @return string 
     */
    public function getTelephon()
    {
        return $this->telephon;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     * @return Member
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string 
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set address
     *
     * @param \AppBundle\Entity\Address $address
     * @return Member
     */
    public function setAddress(\AppBundle\Entity\Address $address = null) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \AppBundle\Entity\Address 
     */
    public function getAddress() {
        return $this->address;
    }
    
    
    /**
     * Set expertLevel
     *
     * @param \AppBundle\Entity\ExpertLevel $expertLevel
     * @return Member
     */
    public function setExpertLevel(\AppBundle\Entity\ExpertLevel $expertLevel = null) {
        $this->expertLevel = $expertLevel;

        return $this;
    }

    /**
     * Get expertLevel
     *
     * @return \AppBundle\Entity\ExpertLevel 
     */
    public function getExpertLevel() {
        return $this->expertLevel;
    }
    
        
    /**
     * Set avatar
     *
     * @param \AppBundle\Entity\Image $avatar
     * @return Member
     */
    public function setAvatar(\AppBundle\Entity\Image $avatar = null) {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \AppBundle\Entity\Image 
     */
    public function getAvatar() {
        return $this->avatar;
    }
    
    /**
     * Add bouteilles
     *
     * @param \AppBundle\Entity\Bouteille $bouteilles
     * @return Member
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
    
    /**
     * Add trocsA
     *
     * @param \AppBundle\Entity\Troc $trocsA
     * @return Member
     */
    public function addTrocA(\AppBundle\Entity\Troc $trocsA) {
        $this->trocsA[] = $trocsA;

        return $this;
    }

    /**
     * Remove trocsA
     *
     * @param \AppBundle\Entity\Troc $trocsA
     */
    public function removeTrocA(\AppBundle\Entity\Troc $trocsA) {
        $this->trocsA->removeElement($trocsA);
    }

    /**
     * Get trocsA
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTrocsA() {
        return $this->trocsA;
    }
    
    /**
     * Add trocsB
     *
     * @param \AppBundle\Entity\Troc $trocsB
     * @return Member
     */
    public function addTrocB(\AppBundle\Entity\Troc $trocsB) {
        $this->trocsB[] = $trocsB;

        return $this;
    }

    /**
     * Remove trocsB
     *
     * @param \AppBundle\Entity\Troc $trocsB
     */
    public function removeTrocB(\AppBundle\Entity\Troc $trocsB) {
        $this->trocsB->removeElement($trocsB);
    }

    /**
     * Get trocsB
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTrocsB() {
        return $this->trocsB;
    }
    
    /**
     * Add trocSections
     *
     * @param \AppBundle\Entity\TrocSection $trocSections
     * @return Member
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
     * Set note
     *
     * @param integer $note
     * @return Member
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return integer 
     */
    public function getNote()
    {
        if($this->note == 0){
            return $this->note;
        }else{
            return round($this->note/$this->totalTroc);
        }
    }
    
    
    /**
     * Update note
     *
     * @param integer $note
     * @return Member
     */
    public function updateNote($note)
    {
        $this->note = $this->note+$note;

        return $this;
    }
    

    /**
     * Set totalTroc
     *
     * @param integer $totalTroc
     * @return Member
     */
    public function setTotalTroc($totalTroc)
    {
        $this->totalTroc = $totalTroc;

        return $this;
    }

    /**
     * Get totalTroc
     *
     * @return integer 
     */
    public function getTotalTroc()
    {
        return $this->totalTroc;
    }
    
    /**
     * Increase totalTroc
     *
     * @param integer $note
     * @return Member
     */
    public function increaseTotalTroc()
    {
        $this->totalTroc++;

        return $this;
    }
    
    
    

    public function generatePassword(PasswordEncoderInterface $encode, SecureRandomInterface $securRandom) {
        $this->salt = md5($securRandom->nextBytes(10));    // Le md5 évite les caractères spé pouvant planter l'interface
        $this->password = $encode->encodePassword($this->password, $this->salt);
    }
    
}
