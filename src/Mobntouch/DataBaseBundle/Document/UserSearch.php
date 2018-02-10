<?php
/**
 * Created by PhpStorm.
 * User: josepmarti
 * Date: 27/10/14
 * Time: 13:02
 */

namespace Mobntouch\DataBaseBundle\Document;


use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;


/**
 * @MongoDB\Document
 */
class UserSearch {

    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    public $userID;

    /**
     * @MongoDB\String
     */
    public $username;

    /**
     * @MongoDB\Boolean
     */
    public $validated;

    /**
     * @MongoDB\String
     */
    public $name;

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
    public $avatar;

    /**
     * @MongoDB\Float
     */
    public $responseRate;

    /**
     * @MongoDB\Int
     */
    public $totalReceivedEmails;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $search;


    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get username
     *
     * @return string $username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set validated
     *
     * @param boolean $validated
     * @return self
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;
        return $this;
    }

    /**
     * Get validated
     *
     * @return boolean $validated
     */
    public function getValidated()
    {
        return $this->validated;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return self
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string $lastname
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set company
     *
     * @param string $company
     * @return self
     */
    public function setCompany($company)
    {
        $this->company = $company;
        return $this;
    }

    /**
     * Get company
     *
     * @return string $company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set jobTitle
     *
     * @param string $jobTitle
     * @return self
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;
        return $this;
    }

    /**
     * Get jobTitle
     *
     * @return string $jobTitle
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return self
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * Get avatar
     *
     * @return string $avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set responseRate
     *
     * @param float $responseRate
     * @return self
     */
    public function setResponseRate($responseRate)
    {
        $this->responseRate = $responseRate;
        return $this;
    }

    /**
     * Get responseRate
     *
     * @return float $responseRate
     */
    public function getResponseRate()
    {
        return $this->responseRate;
    }

    /**
     * Set totalReceivedEmails
     *
     * @param int $totalReceivedEmails
     * @return self
     */
    public function setTotalReceivedEmails($totalReceivedEmails)
    {
        $this->totalReceivedEmails = $totalReceivedEmails;
        return $this;
    }

    /**
     * Get totalReceivedEmails
     *
     * @return int $totalReceivedEmails
     */
    public function getTotalReceivedEmails()
    {
        return $this->totalReceivedEmails;
    }

    /**
     * Set search
     *
     * @param collection $search
     * @return self
     */
    public function setSearch($search)
    {
        $this->search = $search;
        return $this;
    }

    /**
     * Get search
     *
     * @return collection $search
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Set userID
     *
     * @param string $userID
     * @return self
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;
        return $this;
    }

    /**
     * Get userID
     *
     * @return string $userID
     */
    public function getUserID()
    {
        return $this->userID;
    }
}
