<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ODM\CouchDB\Mapping\Annotations as CouchDB;
use AppBundle\Entity\HistoryBO;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Administrator
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AdministratorRepository")
 */
class Administrator extends BaseUser {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __toString() {
        return (string) $this->id;
    }

    public function __construct() {
        parent::__construct();
    }

    public function getLastConnexion() {
        return $this->getLastLogin();
    }

    public function getConfirmedEmail() {
        return true;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function getStatus() {
        $roles = $this->getRoles();
        foreach ($roles as $role) {
            if ($role == 'ROLE_SUPER_ADMIN')
                return 'Super admin';
            if ($role == 'ROLE_ADMIN')
                return 'Admin';
        }
    }

    public function getRole() {
        $roles = $this->getRoles();
        foreach ($roles as $role)
            return $role;
    }

    public function setRole($role) {
        $roles = array($role);
        $this->setRoles($roles);
    }

}
