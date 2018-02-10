<?php

/**
 * Created by PhpStorm.
 * User: josepmarti
 * Date: 27/10/14
 * Time: 13:02
 */

namespace Mobntouch\DataBaseBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
//class User implements UserInterface, EquatableInterface {
class UserRoles {

    /**
     * @MongoDB\Id(strategy="auto")
     */
    public $id;

    /**
     * @MongoDB\String
     */
    public $name;

   
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setname($username) {
        $this->name = $username;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getname() {
        return $this->name;
    }

  
}
