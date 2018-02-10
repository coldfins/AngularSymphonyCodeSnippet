<?php

/**
 * Date: 20/12/16
 * Time: 04:46
 */

namespace Mobntouch\DataBaseBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class College {

    /**
     * @MongoDB\Id(strategy="auto")
     */
    public $id;

    /**
     * @MongoDB\String
     */
    public $username;

    /**
     * @MongoDB\String
     */
    public $name;

    /**
     * @MongoDB\String
     */
    public $avatar;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $degrees;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $students;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $search;

    /**
     * @MongoDB\String
     */
    private $updateDate;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return self
     */
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    /**
     * Get username
     *
     * @return string $username
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return self
     */
    public function setAvatar($avatar) {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * Get avatar
     *
     * @return string $avatar
     */
    public function getAvatar() {
        return $this->avatar;
    }

    /**
     * Set degrees
     *
     * @param collection $degrees
     * @return self
     */
    public function setDegrees($degrees) {
        $this->degrees = $degrees;
        return $this;
    }

    /**
     * Get degrees
     *
     * @return collection $degrees
     */
    public function getDegrees() {
        return $this->degrees;
    }

    /**
     * Set students
     *
     * @param collection $students
     * @return self
     */
    public function setStudents($students) {
        $this->students = $students;
        return $this;
    }

    /**
     * Get students
     *
     * @return collection $students
     */
    public function getStudents() {
        return $this->students;
    }

    /**
     * Set search
     *
     * @param collection $search
     * @return self
     */
    public function setSearch($search) {
        $this->search = $search;
        return $this;
    }

    /**
     * Get search
     *
     * @return collection $search
     */
    public function getSearch() {
        return $this->search;
    }

    /**
     * Set updateDate
     *
     * @param string $updateDate
     * @return self
     */
    public function setUpdateDate($updateDate) {
        $this->updateDate = $updateDate;
        return $this;
    }

    /**
     * Get updateDate
     *
     * @return string $updateDate
     */
    public function getUpdateDate() {
        return $this->updateDate;
    }

}
