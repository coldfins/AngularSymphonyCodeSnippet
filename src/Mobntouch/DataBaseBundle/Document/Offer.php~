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
class Offer {

    /**
     * @MongoDB\Id(strategy="auto")
     */
    public $id;

    /**
     * @MongoDB\String
     */
    public $identifier;
    
    /**
     * @MongoDB\String
     */
    public $date;

    /**
     * @MongoDB\String
     */
    public $updateDate;

    // USER DATA

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


    // OFFER DATA

    /**
     * @MongoDB\String
     */
    public $visibility;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $countries;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $pricingModels;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $platforms;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $quality;

    /**
     * @MongoDB\String
     */
    public $dateRangeModalFormat;

    /**
     * @MongoDB\String
     */
    public $startingTimestamp;

    /**
     * @MongoDB\String
     */
    public $endingTimestamp;

    /**
     * @MongoDB\String
     */
    public $description;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $questions;

    /**
     * @MongoDB\Int
     */
    public $replies;

    /**
     * @MongoDB\Int
     */
    public $pageViews;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $uniquePageViews;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $dailyUniquePageViews;

    /**
     * @MongoDB\Int
     */
    public $conversation;

    /**
     * @MongoDB\String
     */
    public $status;

    /**
     * @MongoDB\String
     */
    public $lastReply;

    /**
     * @MongoDB\String
     */
    public $expiryDate;

    /**
     * @MongoDB\String
     */
    public $expiryTimestamp;

    /**
     * @MongoDB\Boolean
     */
    private $closeOfferEmailNotification;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $history;
    

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
     * Set identifier
     *
     * @param string $identifier
     * @return self
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * Get identifier
     *
     * @return string $identifier
     */
    public function getIdentifier()
    {
        return $this->identifier;
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
     * Set updateDate
     *
     * @param string $updateDate
     * @return self
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;
        return $this;
    }

    /**
     * Get updateDate
     *
     * @return string $updateDate
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
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
     * Set visibility
     *
     * @param string $visibility
     * @return self
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
        return $this;
    }

    /**
     * Get visibility
     *
     * @return string $visibility
     */
    public function getVisibility()
    {
        return $this->visibility;
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
     * Set pricingModels
     *
     * @param collection $pricingModels
     * @return self
     */
    public function setPricingModels($pricingModels)
    {
        $this->pricingModels = $pricingModels;
        return $this;
    }

    /**
     * Get pricingModels
     *
     * @return collection $pricingModels
     */
    public function getPricingModels()
    {
        return $this->pricingModels;
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
     * Set dateRangeModalFormat
     *
     * @param string $dateRangeModalFormat
     * @return self
     */
    public function setDateRangeModalFormat($dateRangeModalFormat)
    {
        $this->dateRangeModalFormat = $dateRangeModalFormat;
        return $this;
    }

    /**
     * Get dateRangeModalFormat
     *
     * @return string $dateRangeModalFormat
     */
    public function getDateRangeModalFormat()
    {
        return $this->dateRangeModalFormat;
    }

    /**
     * Set startingTimestamp
     *
     * @param string $startingTimestamp
     * @return self
     */
    public function setStartingTimestamp($startingTimestamp)
    {
        $this->startingTimestamp = $startingTimestamp;
        return $this;
    }

    /**
     * Get startingTimestamp
     *
     * @return string $startingTimestamp
     */
    public function getStartingTimestamp()
    {
        return $this->startingTimestamp;
    }

    /**
     * Set endingTimestamp
     *
     * @param string $endingTimestamp
     * @return self
     */
    public function setEndingTimestamp($endingTimestamp)
    {
        $this->endingTimestamp = $endingTimestamp;
        return $this;
    }

    /**
     * Get endingTimestamp
     *
     * @return string $endingTimestamp
     */
    public function getEndingTimestamp()
    {
        return $this->endingTimestamp;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
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
     * Set replies
     *
     * @param int $replies
     * @return self
     */
    public function setReplies($replies)
    {
        $this->replies = $replies;
        return $this;
    }

    /**
     * Get replies
     *
     * @return int $replies
     */
    public function getReplies()
    {
        return $this->replies;
    }

    /**
     * Set pageViews
     *
     * @param int $pageViews
     * @return self
     */
    public function setPageViews($pageViews)
    {
        $this->pageViews = $pageViews;
        return $this;
    }

    /**
     * Get pageViews
     *
     * @return int $pageViews
     */
    public function getPageViews()
    {
        return $this->pageViews;
    }

    /**
     * Set uniquePageViews
     *
     * @param collection $uniquePageViews
     * @return self
     */
    public function setUniquePageViews($uniquePageViews)
    {
        $this->uniquePageViews = $uniquePageViews;
        return $this;
    }

    /**
     * Get uniquePageViews
     *
     * @return collection $uniquePageViews
     */
    public function getUniquePageViews()
    {
        return $this->uniquePageViews;
    }

    /**
     * Set conversation
     *
     * @param int $conversation
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
     * @return int $conversation
     */
    public function getConversation()
    {
        return $this->conversation;
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
     * Set lastReply
     *
     * @param string $lastReply
     * @return self
     */
    public function setLastReply($lastReply)
    {
        $this->lastReply = $lastReply;
        return $this;
    }

    /**
     * Get lastReply
     *
     * @return string $lastReply
     */
    public function getLastReply()
    {
        return $this->lastReply;
    }

    /**
     * Set expiryDate
     *
     * @param string $expiryDate
     * @return self
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;
        return $this;
    }

    /**
     * Get expiryDate
     *
     * @return string $expiryDate
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * Set expiryTimestamp
     *
     * @param string $expiryTimestamp
     * @return self
     */
    public function setExpiryTimestamp($expiryTimestamp)
    {
        $this->expiryTimestamp = $expiryTimestamp;
        return $this;
    }

    /**
     * Get expiryTimestamp
     *
     * @return string $expiryTimestamp
     */
    public function getExpiryTimestamp()
    {
        return $this->expiryTimestamp;
    }

    /**
     * Set closeOfferEmailNotification
     *
     * @param boolean $closeOfferEmailNotification
     * @return self
     */
    public function setCloseOfferEmailNotification($closeOfferEmailNotification)
    {
        $this->closeOfferEmailNotification = $closeOfferEmailNotification;
        return $this;
    }

    /**
     * Get closeOfferEmailNotification
     *
     * @return boolean $closeOfferEmailNotification
     */
    public function getCloseOfferEmailNotification()
    {
        return $this->closeOfferEmailNotification;
    }

    /**
     * Set history
     *
     * @param collection $history
     * @return self
     */
    public function setHistory($history)
    {
        $this->history = $history;
        return $this;
    }

    /**
     * Get history
     *
     * @return collection $history
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * Set dailyUniquePageViews
     *
     * @param collection $dailyUniquePageViews
     * @return self
     */
    public function setDailyUniquePageViews($dailyUniquePageViews)
    {
        $this->dailyUniquePageViews = $dailyUniquePageViews;
        return $this;
    }

    /**
     * Get dailyUniquePageViews
     *
     * @return collection $dailyUniquePageViews
     */
    public function getDailyUniquePageViews()
    {
        return $this->dailyUniquePageViews;
    }
}
