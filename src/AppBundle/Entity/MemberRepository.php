<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * MemberRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MemberRepository extends EntityRepository
{
    function findFromSelector($filtres){
        return $this->applicationFilters($filtres, 0, null);
    }
    
    function findFromSelectorWithoutUser($filtres, $idUser, $regionLike){
        return $this->applicationFilters($filtres, $idUser, $regionLike);
    }
    
    function applicationFilters($filtres, $idUser, $regionLike){
        
        $query = $this->createQueryBuilder('m')
                ->leftJoin('m.bouteilles', 'b')
                ->leftJoin('m.expertLevel', 'el')
                ->where("1 = 1")
                ->andWhere("m.actif = 1")
                ->andWhere("m.enabled = 1")
                // à activer uniquement si on ne doit afficher que ceux possédant des bouteilles
                //->andWhere("b.id is not null")
                ->andWhere("m.id <>  :member")
                ->setParameter('member', $idUser);
        
        if(isset($filtres['keyword'])&&($filtres['keyword']!='')){
            $orQuery = $query->expr()->orx();
            $orQuery->add($query->expr()->like("m.lastname", ":keyword"));
            $orQuery->add($query->expr()->like("m.firstname", ":keyword"));
            
            $query->andWhere($orQuery)
                ->setParameter('keyword', '%'.$filtres['keyword'].'%');
        }
        
        if(isset($filtres['expertLevel'])&&($filtres['expertLevel']!='')){
            $query->andWhere('el.id = :expertLevel')
                    ->setParameter('expertLevel', $filtres['expertLevel']);
        }
        
        if(isset($regionLike)&&($regionLike != null)){
            $query->leftJoin('m.address', 'a')
                    ->andWhere($query->expr()->like("a.region", ":region"))
                    ->setParameter('region', $regionLike);
        }
        
        
        if(isset($filtres['filtrageTroqueur'])&&($filtres['filtrageTroqueur']!='')){
            if($filtres['filtrageTroqueur']=='lastname'){
                $query->orderBy('m.lastname', 'ASC');
            }
            if($filtres['filtrageTroqueur']=='niveau'){
                $query->orderBy('el.score', 'DESC');
            }
            if($filtres['filtrageTroqueur']=='note'){
                $query->addSelect('(m.note/m.totalTroc) AS HIDDEN noteMoyenne');
                $query->orderBy('noteMoyenne', 'DESC');
            }
        }

        return $query->getQuery()->getResult();
    }
    
    
    
    
    public function generateTempPassword() {
        $chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789?!&";
        srand((double) microtime() * 1000000);
        $tempPassword = '';
        $len = strlen($chars);
        // On génère un mot de passe de 8 caractères
        for ($i = 0; $i < 8; $i++) {
            //... on choisit un caractère aléatoire dans $chars et on le concatène à $tempPassword
            $tempPassword .= $chars[rand(0, $len - 1)];
        }

        return $tempPassword;
    }
}
