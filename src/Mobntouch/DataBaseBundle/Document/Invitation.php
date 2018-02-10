<?php

/**
 * Created by Ved.
 * User: Ved
 * Date: 08/03/16
 * Time: 12:40
 */

namespace Mobntouch\DataBaseBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Invitation {

    /**
     * @MongoDB\Id(strategy="auto")
     */
    public $id;

    /**
     * @MongoDB\String
     */
    public $userID;

    /**
     * @MongoDB\String
     */
    public $firstname;

    /**
     * @MongoDB\String
     */
    public $lastname;

    /**
     * @MongoDB\String
     */
    public $company;

    /**
     * @MongoDB\String
     */
    public $jobTitle;

    /**
     * @MongoDB\String
     */
    public $email;

    /**
     * @MongoDB\Boolean
     */
    public $isInvited;

    /**
     * @MongoDB\Boolean
     */
    public $isAlreadyExists;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $existingUser;

    /**
     * @MongoDB\String
     */
    public $provider;

    /**
     * @MongoDB\Int
     */
    public $createdDate;

    /**
     * Get id
     *
     * @return string $id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get userID
     *
     * @return string $userID
     */
    public function getUserID() {
        return $this->userID;
    }

    /**
     * Get firstname
     *
     * @return string $firstname
     */
    public function getFirstname() {
        return $this->firstname;
    }

    /**
     * Get lastname
     *
     * @return string $lastname
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * Get company
     *
     * @return string $company
     */
    public function getCompany() {
        return $this->company;
    }

    /**
     * Get jobTitle
     *
     * @return string $jobTitle
     */
    public function getJobTitle() {
        return $this->jobTitle;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Get isInvited
     *
     * @return boolean $isInvited
     */
    public function getIsInvited() {
        return $this->isInvited;
    }

    /**
     * Get isAlreadyExists
     *
     * @return boolean $isAlreadyExists
     */
    public function getIsAlreadyExists() {
        return $this->isAlreadyExists;
    }

    /**
     * Get existingUser
     *
     * @return collection $isAlreadyExists
     */
    public function getExistingUser() {
        return $this->existingUser;
    }

    /**
     * Get provider
     *
     * @return string $provider
     */
    public function getProvider() {
        return $this->provider;
    }

    /**
     * Get createdDate
     *
     * @return Int $createdDate
     */
    public function getCreatedDate() {
        return $this->createdDate;
    }

    /**
     * Set id
     *
     * @param string $id
     * @return self
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Set userId
     *
     * @param string $userID
     * @return self
     */
    public function setUserID($userID) {
        $this->userID = $userID;
        return $this;
    }

    /**
     * Set userId
     *
     * @param string $firstname
     * @return self
     */
    public function setFirstname($firstname) {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * Set userId
     *
     * @param string $lastname
     * @return self
     */
    public function setLastname($lastname) {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * Set company
     *
     * @param string $company
     * @return self
     */
    public function setCompany($company) {
        $this->company = $company;
        return $this;
    }

    /**
     * Set jobTitle
     *
     * @param string $jobTitle
     * @return self
     */
    public function setJobTitle($jobTitle) {
        $this->jobTitle = $jobTitle;
        return $this;
    }

    /**
     * Set userId
     *
     * @param string $email
     * @return self
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     * Set userId
     *
     * @param boolean $isinvited
     * @return self
     */
    public function setIsInvited($isInvited) {
        $this->isInvited = $isInvited;
        return $this;
    }

    /**
     * Set isAlreadyExists
     *
     * @param boolean $isAlreadyExists
     * @return self
     */
    public function setIsAlreadyExists($isAlreadyExists) {
        $this->isAlreadyExists = $isAlreadyExists;
        return $this;
    }

    /**
     * Set existingUser
     *
     * @param collection $existingUser
     * @return self
     */
    public function setExistingUser($existingUser) {
        $this->existingUser = $existingUser;
        return $this;
    }

    /**
     * Set Provider
     *
     * @param String $provider
     * @return self
     */
    public function setProvider($provider) {
        $this->provider = $provider;
        return $this;
    }

    /**
     * Set CreatedDate
     *
     * @param Int $createddate
     * @return self
     */
    public function setCreatedDate($createdDate) {
        $this->createdDate = $createdDate;
        return $this;
    }

}
