<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\TypeAppellation;
use AppBundle\Entity\TypeContenance;
use AppBundle\Entity\TypeCuvee;
use AppBundle\Entity\TypeDeCave;
use AppBundle\Entity\TypeDeVin;
use AppBundle\Entity\TypeDomaine;
use AppBundle\Entity\TypePays;
use AppBundle\Entity\TypeRegion;
use AppBundle\Entity\Image;
use AppBundle\Entity\Member;

/**
 * Bouteille
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\BouteilleRepository")
 */
class Bouteille
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
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @var integer
     *
     * @ORM\Column(name="note", type="integer")
     */
    private $note;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=255, nullable=true)
     */
    private $commentaire;

    /**
     * @var boolean
     *
     * @ORM\Column(name="boite_origine", type="boolean", options={"default":0})
     */
    private $boiteOrigine;

    /**
     * @var integer
     *
     * @ORM\Column(name="niveau", type="integer")
     */
    private $niveau;

    /**
     * @var boolean
     *
     * @ORM\Column(name="online", type="boolean", options={"default":0})
     */
    private $online;

    /**
     * @var boolean
     *
     * @ORM\Column(name="reserved", type="boolean", options={"default":0})
     */
    private $reserved;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="TypeDeVin", inversedBy="bouteilles")
     * @ORM\JoinColumn(name="type_de_vin_id", referencedColumnName="id")
     */
    protected $typeDeVin;
    
    /**
     * @ORM\ManyToOne(targetEntity="TypeDomaine", inversedBy="bouteilles")
     * @ORM\JoinColumn(name="type_domaine_id", referencedColumnName="id")
     */
    protected $typeDomaine;
    
    /**
     * @ORM\ManyToOne(targetEntity="TypeAppellation", inversedBy="bouteilles")
     * @ORM\JoinColumn(name="type_appellation_id", referencedColumnName="id")
     */
    protected $typeAppellation;
    
    /**
     * @ORM\ManyToOne(targetEntity="TypeCuvee", inversedBy="bouteilles")
     * @ORM\JoinColumn(name="type_cuvee_id", referencedColumnName="id")
     */
    protected $typeCuvee;
    
    /**
     * @ORM\ManyToOne(targetEntity="TypeRegion", inversedBy="bouteilles")
     * @ORM\JoinColumn(name="type_region_id", referencedColumnName="id")
     */
    protected $typeRegion;
    
    /**
     * @ORM\ManyToOne(targetEntity="TypePays", inversedBy="bouteilles")
     * @ORM\JoinColumn(name="type_pays_id", referencedColumnName="id")
     */
    protected $typePays;
        
    /**
     * @var integer
     *
     * @ORM\Column(name="millesime", type="integer")
     */
    private $millesime;
        
    /**
     * @var integer
     *
     * @ORM\Column(name="apogee", type="integer", nullable=true)
     */
    private $apogee;
    
    /**
     * @ORM\ManyToOne(targetEntity="TypeContenance", inversedBy="bouteilles")
     * @ORM\JoinColumn(name="type_contenance_id", referencedColumnName="id")
     */
    protected $typeContenance;
    
    /**
     * @ORM\ManyToOne(targetEntity="TypeDeCave", inversedBy="bouteilles")
     * @ORM\JoinColumn(name="type_de_cave_id", referencedColumnName="id")
     */
    protected $typeDeCave;
    
    /**
     * @ORM\OneToOne(targetEntity="Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     * */
    private $photo;
    
    /**
     * @ORM\OneToOne(targetEntity="Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="image_niveau_id", referencedColumnName="id")
     * */
    private $photoNiveau;
    
    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="bouteilles")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    protected $member;

    /**
     * @ORM\OneToMany(targetEntity="TrocBouteille", mappedBy="bouteille", cascade={"persist", "remove"})
     */
    protected $inTroc;


    public function __toString() {
        return (string) $this->id;
    }
    
    public function __construct() {
        $this->online = false;
        $this->reserved = 0;
        $this->createdAt = new \Symfony\Component\Validator\Constraints\DateTime();
        $this->inTroc = new ArrayCollection();
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
     * Set quantite
     *
     * @param integer $quantite
     * @return Bouteille
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer 
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set note
     *
     * @param integer $note
     * @return Bouteille
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
        return $this->note;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Bouteille
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set boiteOrigine
     *
     * @param boolean $boiteOrigine
     * @return Bouteille
     */
    public function setBoiteOrigine($boiteOrigine)
    {
        $this->boiteOrigine = $boiteOrigine;

        return $this;
    }

    /**
     * Get boiteOrigine
     *
     * @return boolean 
     */
    public function getBoiteOrigine()
    {
        return $this->boiteOrigine;
    }

    /**
     * Set niveau
     *
     * @param integer $niveau
     * @return Bouteille
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return integer 
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Set online
     *
     * @param boolean $online
     * @return Bouteille
     */
    public function setOnline($online)
    {
        $this->online = $online;

        return $this;
    }

    /**
     * Get online
     *
     * @return boolean 
     */
    public function getOnline()
    {
        return $this->online;
    }

    /**
     * Set reserved
     *
     * @param boolean $reserved
     * @return Bouteille
     */
    public function setReserved($reserved)
    {
        $this->reserved = $reserved;

        return $this;
    }

    /**
     * Get online
     *
     * @return boolean 
     */
    public function getReserved()
    {
        return $this->reserved;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Bouteille
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
     * Set apogee
     *
     * @param integer $apogee
     * @return Bouteille
     */
    public function setApogee($apogee)
    {
        $this->apogee = $apogee;

        return $this;
    }

    /**
     * Get apogee
     *
     * @return integer 
     */
    public function getApogee()
    {
        return $this->apogee;
    }
    
    /**
     * Set typeAppellation
     *
     * @param \AppBundle\Entity\TypeAppellation $typeAppellation
     * @return Bouteille
     */
    public function setTypeAppellation(\AppBundle\Entity\TypeAppellation $typeAppellation = null) {
        $this->typeAppellation = $typeAppellation;

        return $this;
    }

    /**
     * Get typeAppellation
     *
     * @return \AppBundle\Entity\TypeAppellation 
     */
    public function getTypeAppellation() {
        return $this->typeAppellation;
    }
    
    /**
     * Set typeContenance
     *
     * @param \AppBundle\Entity\TypeContenance $typeContenance
     * @return Bouteille
     */
    public function setTypeContenance(\AppBundle\Entity\TypeContenance $typeContenance = null) {
        $this->typeContenance = $typeContenance;

        return $this;
    }

    /**
     * Get typeContenance
     *
     * @return \AppBundle\Entity\TypeContenance 
     */
    public function getTypeContenance() {
        return $this->typeContenance;
    }
    
    
    /**
     * Set typeCuvee
     *
     * @param \AppBundle\Entity\TypeCuvee $typeCuvee
     * @return Bouteille
     */
    public function setTypeCuvee(\AppBundle\Entity\TypeCuvee $typeCuvee = null) {
        $this->typeCuvee = $typeCuvee;

        return $this;
    }

    /**
     * Get typeCuvee
     *
     * @return \AppBundle\Entity\TypeCuvee 
     */
    public function getTypeCuvee() {
        return $this->typeCuvee;
    }
    
    
    /**
     * Set typeDeCave
     *
     * @param \AppBundle\Entity\TypeDeCave $typeDeCave
     * @return Bouteille
     */
    public function setTypeDeCave(\AppBundle\Entity\TypeDeCave $typeDeCave = null) {
        $this->typeDeCave = $typeDeCave;

        return $this;
    }

    /**
     * Get typeDeCave
     *
     * @return \AppBundle\Entity\TypeDeCave 
     */
    public function getTypeDeCave() {
        return $this->typeDeCave;
    }
    
    
    /**
     * Set typeDeVin
     *
     * @param \AppBundle\Entity\TypeDeVin $typeDeVin
     * @return Bouteille
     */
    public function setTypeDeVin(\AppBundle\Entity\TypeDeVin $typeDeVin = null) {
        $this->typeDeVin = $typeDeVin;

        return $this;
    }

    /**
     * Get typeDeVin
     *
     * @return \AppBundle\Entity\TypeDeVin 
     */
    public function getTypeDeVin() {
        return $this->typeDeVin;
    }
    
    
    /**
     * Set typeDomaine
     *
     * @param \AppBundle\Entity\TypeDomaine $typeDomaine
     * @return Bouteille
     */
    public function setTypeDomaine(\AppBundle\Entity\TypeDomaine $typeDomaine = null) {
        $this->typeDomaine = $typeDomaine;

        return $this;
    }

    /**
     * Get typeDomaine
     *
     * @return \AppBundle\Entity\TypeDomaine 
     */
    public function getTypeDomaine() {
        return $this->typeDomaine;
    }
    
    
    /**
     * Set millesime
     *
     * @param integer $millesime
     * @return Bouteille
     */
    public function setMillesime($millesime)
    {
        $this->millesime = $millesime;

        return $this;
    }

    /**
     * Get millesime
     *
     * @return integer 
     */
    public function getMillesime()
    {
        return $this->millesime;
    }
    
    
    /**
     * Set typePays
     *
     * @param \AppBundle\Entity\TypePays $typePays
     * @return Bouteille
     */
    public function setTypePays(\AppBundle\Entity\TypePays $typePays = null) {
        $this->typePays = $typePays;

        return $this;
    }

    /**
     * Get typePays
     *
     * @return \AppBundle\Entity\TypePays 
     */
    public function getTypePays() {
        return $this->typePays;
    }
    
    
    /**
     * Set typeRegion
     *
     * @param \AppBundle\Entity\TypeRegion $typeRegion
     * @return Bouteille
     */
    public function setTypeRegion(\AppBundle\Entity\TypeRegion $typeRegion = null) {
        $this->typeRegion = $typeRegion;

        return $this;
    }

    /**
     * Get typeRegion
     *
     * @return \AppBundle\Entity\TypeRegion 
     */
    public function getTypeRegion() {
        return $this->typeRegion;
    }
    
    
    /**
     * Set photo
     *
     * @param \AppBundle\Entity\Image $photo
     * @return Bouteille
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
     * Set photoNiveau
     *
     * @param \AppBundle\Entity\Image $photoNiveau
     * @return Bouteille
     */
    public function setPhotoNiveau(\AppBundle\Entity\Image $photoNiveau = null) {
        $this->photoNiveau = $photoNiveau;

        return $this;
    }

    /**
     * Get photoNiveau
     *
     * @return \AppBundle\Entity\Image 
     */
    public function getPhotoNiveau() {
        return $this->photoNiveau;
    }
    
    
    
    /**
     * Set member
     *
     * @param \AppBundle\Entity\Member $member
     * @return Bouteille
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
     * Add inTroc
     *
     * @param \AppBundle\Entity\TrocBouteille $inTroc
     * @return Bouteille
     */
    public function addInTroc(\AppBundle\Entity\TrocBouteille $inTroc) {
        $this->inTroc[] = $inTroc;

        return $this;
    }

    /**
     * Remove inTroc
     *
     * @param \AppBundle\Entity\TrocBouteille $inTroc
     */
    public function removeInTroc(\AppBundle\Entity\TrocBouteille $inTroc) {
        $this->inTroc->removeElement($inTroc);
    }

    /**
     * Get inTroc
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInTroc() {
        return $this->inTroc;
    }
}
