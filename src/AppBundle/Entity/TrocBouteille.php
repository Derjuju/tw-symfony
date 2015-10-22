<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrocBouteille
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TrocBouteilleRepository")
 */
class TrocBouteille
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
     * @ORM\ManyToOne(targetEntity="Bouteille", inversedBy="inTroc")
     * @ORM\JoinColumn(name="bouteille_id", referencedColumnName="id")
     * */
    protected $bouteille;
    
    /**
     * @ORM\ManyToOne(targetEntity="TrocContenu", inversedBy="trocABouteilles")
     * @ORM\JoinColumn(name="troc_contenu_a_id", referencedColumnName="id")
     */
    protected $trocContenuA;
    
    /**
     * @ORM\ManyToOne(targetEntity="TrocContenu", inversedBy="trocBBouteilles")
     * @ORM\JoinColumn(name="troc_contenu_b_id", referencedColumnName="id")
     */
    protected $trocContenuB;
    
    
    
    public function __toString() {
        return (string) $this->id;
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
     * @return TrocBouteille
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
     * Set bouteille
     *
     * @param \AppBundle\Entity\Bouteille $bouteille
     * @return TrocBouteille
     */
    public function setBouteille(\AppBundle\Entity\Bouteille $bouteille = null) {
        $this->bouteille = $bouteille;

        return $this;
    }

    /**
     * Get bouteille
     *
     * @return \AppBundle\Entity\Bouteille 
     */
    public function getBouteille() {
        return $this->bouteille;
    }
    
    /**
     * Set trocContenuA
     *
     * @param \AppBundle\Entity\TrocContenu $trocContenuA
     * @return TrocBouteille
     */
    public function setTrocContenuA(\AppBundle\Entity\TrocContenu $trocContenuA = null)
    {
        $this->trocContenuA = $trocContenuA;

        return $this;
    }

    /**
     * Get trocContenuA
     *
     * @return \AppBundle\Entity\TrocContenu 
     */
    public function getTrocContenuA()
    {
        return $this->trocContenuA;
    }
    
    /**
     * Set trocContenuB
     *
     * @param \AppBundle\Entity\TrocContenu $trocContenuB
     * @return TrocBouteille
     */
    public function setTrocContenuB(\AppBundle\Entity\TrocContenu $trocContenuB = null)
    {
        $this->trocContenuB = $trocContenuB;

        return $this;
    }

    /**
     * Get trocContenuB
     *
     * @return \AppBundle\Entity\TrocContenu 
     */
    public function getTrocContenuB()
    {
        return $this->trocContenuB;
    }
}
