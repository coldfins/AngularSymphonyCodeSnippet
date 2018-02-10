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
class OfferReply {

    /**
     * @MongoDB\Id(strategy="auto")
     */
    public $id;

    /**
     * @MongoDB\String
     */
    public $offerID;

    /**
     * @MongoDB\String
     */
    public $userID;

    /**
     * @MongoDB\String
     */
    public $username;

    /**
     * @MongoDB\String
     */
    public $userAvatar;

    /**
     * @MongoDB\String
     */
    public $userFirstName;

    /**
     * @MongoDB\String
     */
    public $userLastName;

    /**
     * @MongoDB\String
     */
    public $userCompany;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $countries;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $pricing;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $platforms;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $quality;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $questions;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $conversation;

    /**
     * @MongoDB\String
     */
    public $date;

    /**
     * @MongoDB\String
     */
    public $conversationDate;

    /**
     * @MongoDB\String
     */
    public $lastUpdate;

    /**
     * @MongoDB\String
     */
    public $status;
    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $read;
    

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
     * Set offerID
     *
     * @param string $offerID
     * @return self
     */
    public function setOfferID($offerID)
    {
        $this->offerID = $offerID;
        return $this;
    }

    /**
     * Get offerID
     *
     * @return string $offerID
     */
    public function getOfferID()
    {
        return $this->offerID;
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
     * Set userAvatar
     *
     * @param string $userAvatar
     * @return self
     */
    public function setUserAvatar($userAvatar)
    {
        $this->userAvatar = $userAvatar;
        return $this;
    }

    /**
     * Get userAvatar
     *
     * @return string $userAvatar
     */
    public function getUserAvatar()
    {
        return $this->userAvatar;
    }

    /**
     * Set userFirstName
     *
     * @param string $userFirstName
     * @return self
     */
    public function setUserFirstName($userFirstName)
    {
        $this->userFirstName = $userFirstName;
        return $this;
    }

    /**
     * Get userFirstName
     *
     * @return string $userFirstName
     */
    public function getUserFirstName()
    {
        return $this->userFirstName;
    }

    /**
     * Set userLastName
     *
     * @param string $userLastName
     * @return self
     */
    public function setUserLastName($userLastName)
    {
        $this->userLastName = $userLastName;
        return $this;
    }

    /**
     * Get userLastName
     *
     * @return string $userLastName
     */
    public function getUserLastName()
    {
        return $this->userLastName;
    }

    /**
     * Set userCompany
     *
     * @param string $userCompany
     * @return self
     */
    public function setUserCompany($userCompany)
    {
        $this->userCompany = $userCompany;
        return $this;
    }

    /**
     * Get userCompany
     *
     * @return string $userCompany
     */
    public function getUserCompany()
    {
        return $this->userCompany;
    }

    /**
     * Set countries
     *
     * @param collection $countries
     * @return self
     */
    public function setCountries($countries)
    {
        $this->countries = $countries;
        return $this;
    }

    /**
     * Get countries
     *
     * @return collection $countries
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * Set pricing
     *
     * @param collection $pricing
     * @return self
     */
    public function setPricing($pricing)
    {
        $this->pricing = $pricing;
        return $this;
    }

    /**
     * Get pricing
     *
     * @return collection $pricing
     */
    public function getPricing()
    {
        return $this->pricing;
    }

    /**
     * Set platforms
     *
     * @param collection $platforms
     * @return self
     */
    public function setPlatforms($platforms)
    {
        $this->platforms = $platforms;
        return $this;
    }

    /**
     * Get platforms
     *
     * @return collection $platforms
     */
    public function getPlatforms()
    {
        return $this->platforms;
    }

    /**
     * Set quality
     *
     * @param collection $quality
     * @return self
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;
        return $this;
    }

    /**
     * Get quality
     *
     * @return collection $quality
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * Set questions
     *
     * @param collection $questions
     * @return self
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;
        return $this;
    }

    /**
     * Get questions
     *
     * @return collection $questions
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set conversation
     *
     * @param collection $conversation
     * @return self
     */
    public function setConversation($conversation)
    {
        $this->conversation = $conversation;
        return $this;
    }

    /**
     * Get conversation
     *
     * @return collection $conversation
     */
    public function getConversation()
    {
        return $this->conversation;
    }

    /**
     * Set date
     *
     * @param string $date
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return string $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set conversationDate
     *
     * @param string $conversationDate
     * @return self
     */
    public function setConversationDate($conversationDate)
    {
        $this->conversationDate = $conversationDate;
        return $this;
    }

    /**
     * Get conversationDate
     *
     * @return string $conversationDate
     */
    public function getConversationDate()
    {
        return $this->conversationDate;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return string $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set read
     *
     * @param collection $read
     * @return self
     */
    public function setRead($read)
    {
        $this->read = $read;
        return $this;
    }

    /**
     * Get read
     *
     * @return collection $read
     */
    public function getRead()
    {
        return $this->read;
    }

    /**
     * Set lastUpdate
     *
     * @param string $lastUpdate
     * @return self
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return string $lastUpdate
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }
}
