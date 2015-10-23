<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BouteilleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BouteilleRepository extends EntityRepository
{
    function findForIdAndUser($id, $member){
        $query = $this->createQueryBuilder('b')
                ->where('b.member = :member')
                ->andWhere('b.id = :id')
                ->setParameter('member', $member)
                ->setParameter('id', $id);

        return $query->getQuery()->getResult();
    }
    
    function findOthersFrom($id, $member){
        $query = $this->createQueryBuilder('b')
                ->where('b.member = :member')
                ->andWhere('b.id <> :id')
                ->setParameter('member', $member)
                ->setParameter('id', $id);

        return $query->getQuery()->getResult();
    }
    
    
    function findFromSelector($filtres){
        return $this->applicationFilters($filtres, 0);
    }
    
    function findFromSelectorWithoutUser($filtres, $idUser){
        return $this->applicationFilters($filtres, $idUser);
    }
    
    function applicationFilters($filtres, $idUser){
        
        $query = $this->createQueryBuilder('b')
                ->leftJoin('b.typeDeVin', 'tdv')
                ->leftJoin('b.typeDomaine', 'td')
                ->leftJoin('b.typeAppellation', 'ta')
                ->leftJoin('b.typeCuvee', 'tc')
                ->leftJoin('b.typeRegion', 'tr')
                ->leftJoin('b.typePays', 'tp')
                ->where("1 = 1")
                ->andWhere("b.online = 1")
                ->andWhere("b.reserved = 0")
                ->andWhere("b.member <>  :member")
                ->setParameter('member', $idUser);
        
        if(isset($filtres['keyword'])&&($filtres['keyword']!='')){
            $orQuery = $query->expr()->orx();
            $orQuery->add($query->expr()->like("b.commentaire", ":keyword"));
            $orQuery->add($query->expr()->like("tdv.nameFr", ":keyword"));
            $orQuery->add($query->expr()->like("tdv.nameUk", ":keyword"));
            $orQuery->add($query->expr()->like("td.nameFr", ":keyword"));
            $orQuery->add($query->expr()->like("td.nameUk", ":keyword"));
            $orQuery->add($query->expr()->like("ta.nameFr", ":keyword"));
            $orQuery->add($query->expr()->like("ta.nameUk", ":keyword"));
            $orQuery->add($query->expr()->like("tc.nameFr", ":keyword"));
            $orQuery->add($query->expr()->like("tc.nameUk", ":keyword"));
            $orQuery->add($query->expr()->like("tr.nameFr", ":keyword"));
            $orQuery->add($query->expr()->like("tr.nameUk", ":keyword"));
            $orQuery->add($query->expr()->like("tp.nameFr", ":keyword"));
            $orQuery->add($query->expr()->like("tp.nameUk", ":keyword"));
            
            $query->andWhere($orQuery)
                ->setParameter('keyword', $filtres['keyword']);
        }
        if(isset($filtres['typeDeVin'])&&($filtres['typeDeVin']!='')){
            $query->andWhere('tdv.id = :typeDeVin')
                ->setParameter('typeDeVin', $filtres['typeDeVin']);
        }
        if(isset($filtres['typeRegion'])&&($filtres['typeRegion']!='')){
            if($filtres['typeRegion']!=-1){
                $query->andWhere('tr.id = :typeRegion')
                    ->setParameter('typeRegion', $filtres['typeRegion']);
            }else{
                $query->andWhere($query->expr()->notIn('tr.id', $filtres['typeRegionAExclure']));
            }
        }
        if(isset($filtres['millesime'])&&($filtres['millesime']!='')){
            $query->andWhere('b.millesime = :millesime')
                ->setParameter('millesime', $filtres['millesime']);
        }
        if(isset($filtres['typePays'])&&($filtres['typePays']!='')){
            $query->andWhere('tp.id = :typePays')
                ->setParameter('typePays', $filtres['typePays']);
        }
        
        
        
        if(isset($filtres['filtrageBottle'])&&($filtres['filtrageBottle']!='')){
            if($filtres['filtrageBottle']=='millesime'){
                $query->orderBy('b.millesime', 'ASC');
            }
            if($filtres['filtrageBottle']=='qte'){
                $query->orderBy('b.quantite', 'DESC');
            }
        }

        return $query->getQuery()->getResult();
    }
}
