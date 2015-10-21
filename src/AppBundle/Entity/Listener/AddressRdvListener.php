<?php

namespace AppBundle\Entity\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\AddressRdv;

/**
 * Description of AddressRdvListener
 *
 * @author pinacolada
 */

class AddressRdvListener
{
    
    private $em;
    
    public function prePersist(Address $address, LifecycleEventArgs $event)
    {
        $this->em = $event->getEntityManager();        
        $address = $this->geolocaliseAddress($address, true);        
    }
    
    public function preUpdate(Address $address, LifecycleEventArgs $event)
    {
        $this->em = $event->getEntityManager();        
        $address = $this->geolocaliseAddress($address, false);
    }
    
    public function postUpdate(Address $address,LifecycleEventArgs $event)
    {
        $this->em = $event->getEntityManager(); 
    }
    
    private function geolocaliseAddress(Address $address, $force){
        $uow = $this->em->getUnitOfWork();
        $changeSet = $uow->getEntityChangeSet($address);
        
        // si des éléments changent dans l'adresse alors on met à jour les geoloc
        if((count($changeSet)>0)||($force)){
            $adresse_pour_geoloc = "";
            if($address->getStreet()!= null)
            {
                $adresse_pour_geoloc .= $address->getStreet().", ";
            }
            if($address->getZipCode()!= null)
            {
                $adresse_pour_geoloc .= $address->getZipCode().", ";
            }
            if($address->getCity()!= null)
            {
                $adresse_pour_geoloc .= $address->getCity().", ";
            }
            if($address->getCountry()!= null)
            {
                $adresse_pour_geoloc .= $address->getCountry();
            }
            
            $addressclean = str_replace (" ", "+", $adresse_pour_geoloc);
            
            $geoloc_url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $addressclean . "&sensor=false";
            
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $geoloc_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $geoloc = json_decode(curl_exec($ch), true);
            
            $address->setLatitude(null);
            $address->setLongitude(null);
            
            if(isset($geoloc['results'][0])){
                if(isset($geoloc['results'][0]['geometry']['location']['lat'])){
                    $lat = $geoloc['results'][0]['geometry']['location']['lat'];
                    $lng = $geoloc['results'][0]['geometry']['location']['lng'];

                    $address->setLatitude($lat);
                    $address->setLongitude($lng);
                }
            }
            
            return $address;
            
        }
    }
}