<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AddressRdv
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AddressRdvRepository")
 * @ORM\EntityListeners({ "AppBundle\Entity\Listener\AddressRdvListener" })
 */
class AddressRdv
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name = null;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255, nullable=true)
     */
    private $street = null;

    /**
     * @var integer
     *
     * @ORM\Column(name="zip_code", type="integer", nullable=true)
     */
    private $zipCode = null;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city = null;

    /**
     * @var string
     *
     * @ORM\Column(name="departement", type="string", length=255, nullable=true)
     */
    private $departement = null;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=255, nullable=true)
     */
    private $region = null;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country = null;

    /**
     * @var decimal
     *
     * @ORM\Column(name="latitude", type="decimal", precision=16, scale=14, nullable=true, options={"comment":"geoloc latitude"})
     */
    private $latitude = null;

    /**
     * @var decimal
     *
     * @ORM\Column(name="longitude", type="decimal", precision=17, scale=14, nullable=true, options={"comment":"geoloc longitude"})
     */
    private $longitude = null;


    /**
     * @ORM\ManyToOne(targetEntity="TrocRDV", inversedBy="addressRdvs")
     * @ORM\JoinColumn(name="troc_rdv_id", referencedColumnName="id")
     * */
    protected $trocRdv;

    /**
     * @var boolean
     *
     * @ORM\Column(name="checked_for_suggestion", type="integer", nullable=true)
     */
    private $checkedForSuggestion;
    
    

    public function __toString() {
        return $this->street . ' ' . $this->city;
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
     * Set name
     *
     * @param string $name
     * @return AddressRdv
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return AddressRdv
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set zipCode
     *
     * @param integer $zipCode
     * @return AddressRdv
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return integer 
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return AddressRdv
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set departement
     *
     * @param string $departement
     * @return AddressRdv
     */
    public function setDepartement($departement)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return string 
     */
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     * Set region
     *
     * @param string $region
     * @return AddressRdv
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return AddressRdv
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return AddressRdv
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return AddressRdv
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
    
    /**
     * Set trocRdv
     *
     * @param \AppBundle\Entity\TrocRDV $trocRdv
     * @return AddressRdv
     */
    public function setTrocRdv(\AppBundle\Entity\TrocRDV $trocRdv = null) {
        $this->trocRdv = $trocRdv;

        return $this;
    }

    /**
     * Get trocRdv
     *
     * @return \AppBundle\Entity\TrocRDV 
     */
    public function getTrocRdv() {
        return $this->trocRdv;
    }
    
    
    /**
     * Set checkedForSuggestion
     *
     * @param integer $checkedForSuggestion
     * @return TrocRDV
     */
    public function setCheckedForSuggestion($checkedForSuggestion)
    {
        $this->checkedForSuggestion = $checkedForSuggestion;

        return $this;
    }

    /**
     * Get checkedForSuggestion
     *
     * @return integer 
     */
    public function getCheckedForSuggestion()
    {
        return $this->checkedForSuggestion;
    }
}
