<?php

namespace AppBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of VarsExtension
 *
 * @author pinacolada
 */
class VarsExtension extends \Twig_Extension {

    protected $container;
    private $locale;

    public function __construct(ContainerInterface $container, $locale) {
        $this->container = $container;
        $this->locale = $locale;
    }

    public function getName() {
        return 'vars_extension';
    }

    public function getFilters() {
        return array(
            'filtre_millesime' => new \Twig_Filter_Method($this, 'filtreMillesime'),
            'display_as_letters' => new \Twig_Filter_Method($this, 'displayAsLetters'),
        );
    }

    public function filtreMillesime($millesime) {
        $str = $millesime;
        if($millesime == 0){
            //if($this->locale == "fr"){
                $str = "Non millésimé";
            //}
        }
        return $str;
    }
    
    public function displayAsLetters($mois) {
        $str = $mois;
        switch($mois){
            case 1 : $str = "Janvier"; break;
            case 2 : $str = "Février"; break;
            case 3 : $str = "Mars"; break;
            case 4 : $str = "Avril"; break;
            case 5 : $str = "Mai"; break;
            case 6 : $str = "Juin"; break;
            case 7 : $str = "Juillet"; break;
            case 8 : $str = "Août"; break;
            case 9 : $str = "Septembre"; break;
            case 10 : $str = "Octobre"; break;
            case 11 : $str = "Novembre"; break;
            case 12 : $str = "Décembre"; break;
        }
        return $str;
    }
}
