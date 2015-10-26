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
}
