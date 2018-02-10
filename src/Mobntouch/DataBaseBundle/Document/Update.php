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
class Update {
    // Class to handle regular action updates and likes

    /**
     * @MongoDB\Id(strategy="auto")
     */
    public $id;

    /**
     * @MongoDB\String
     */
    public $date;

    // USER = performer user // user who did the action in the first place
    /**
     * @MongoDB\String
     */
    public $userID; // Used to fetch the user's feed

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
    public $userFullName;

    /**
     * @MongoDB\String
     */
    public $userJobTitle;

    /**
     * @MongoDB\Int
     */
    public $type;

    /**
     * @MongoDB\String
     */
    public $filter;

    /**
     * @MongoDB\String
     */
    public $action;

    /**
     * @MongoDB\Int
     */
    public $likesCounter;
    
    /**
     * @MongoDB\Int
     */
    public $commentsCounter;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $liked;

    // $liked is an Array of:
    // UserID
    // Username
    // UserAvatar
    // UserFullName
    // UserJobTitle


    // LIKE FEED VARIABLES

    /**
     * @MongoDB\String
     */
    public $updateID;

    /**
     * @MongoDB\Boolean
     */
    public $isLike; // Is this a like update ?

    /**
     * @MongoDB\Boolean
     */
    public $isComment; // Is this a like update ?

    /**
     * @MongoDB\Boolean
     */
    public $like; // Value of the like for this user

    /**
     * @MongoDB\String
     */
    public $likeUserID;

    /**
     * @MongoDB\String
     */
    public $likeUsername;

    /**
     * @MongoDB\String
     */
    public $likeUserFullName;

    /**
     * @MongoDB\String
     */
    public $likeUserAvatar;

    // INTOUCH

    /**
     * @MongoDB\String
     */
    public $inTouchID; // Used to fetch the user's feed

    /**
     * @MongoDB\String
     */
    public $inTouchUsername;

    /**
     * @MongoDB\String
     */
    public $inTouchAvatar;

    /**
     * @MongoDB\String
     */
    public $inTouchFullName;

    /**
     * @MongoDB\String
     */
    public $inTouchJobTitle;

    /**
     * @MongoDB\String
     */
    public $inTouchCompany;

    // NEWS

    /**
     * @MongoDB\String
     */
    public $newsID; // Used to fetch the user's feed

    /**
     * @MongoDB\String
     */
    public $sourceID;

    /**
     * @MongoDB\String
     */
    public $newsTitle;

    /**
     * @MongoDB\String
     */
    public $newsSummary;

    /**
     * @MongoDB\String
     */
    public $newsImage;

    /**
     * @MongoDB\String
     */
    public $newsSource;

    /**
     * @MongoDB\String
     */
    public $newsSourceURL;

    /**
     * @MongoDB\String
     */
    public $newsURL;

    /**
     * @MongoDB\String
     */
    public $newsDate; // ?



    // FOLLOW COMPANY

    /**
     * @MongoDB\String
     */
    public $companyID; // Used to fetch the user's feed

    /**
     * @MongoDB\String
     */
    public $companyUsername;

    /**
     * @MongoDB\String
     */
    public $companyAvatar;

    /**
     * @MongoDB\String
     */
    public $companyFullName;

    /**
     * @MongoDB\Int
     */
    public $companyFollowers;


    /**
     * @MongoDB\String
     */
    public $companyType;

    /**
     * @MongoDB\String
     */
    public $postText;

    /**
     * @MongoDB\String
     */
    public $postImage;

    /**
     * @MongoDB\String
     */
    public $postYoutube;

    /**
     * @MongoDB\String
     */
    public $postVimeo;


    // EVENT

    /**
     * @MongoDB\String
     */
    public $eventID; // Used to fetch the user's feed

    /**
     * @MongoDB\String
     */
    public $eventUsername;

    /**
     * @MongoDB\String
     */
    public $eventFullName;

    /**
     * @MongoDB\String
     */
    public $eventText;

    /**
     * @MongoDB\String
     */
    public $eventImg;

    /**
     * @MongoDB\String
     */
    public $eventLink;

    /**
     * @MongoDB\String
     */
    public $eventLinkRegister;

    /**
     * @MongoDB\String
     */
    public $eventCity;

    /**
     * @MongoDB\String
     */
    public $eventDate;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $comments;


    // Share User Profile

    /**
     * @MongoDB\String
     */
    public $sharedUsername;
    /**
     * @MongoDB\String
     */
    public $sharedUserAvatar;
    /**
     * @MongoDB\String
     */
    public $sharedUserFullName;
    /**
     * @MongoDB\String
     */
    public $sharedUserJobTitle;
    /**
     * @MongoDB\String
     */
    public $sharedUserCompany;

    // Share Company Page

    /**
     * @MongoDB\String
     */
    public $sharedCompanyUsername;
    /**
     * @MongoDB\String
     */
    public $sharedCompanyAvatar;
    /**
     * @MongoDB\String
     */
    public $sharedCompanyName;
    /**
     * @MongoDB\String
     */
    public $sharedCompanyFollowers;
    /**
     * @MongoDB\String
     */
    public $sharedCompanyType;
    
    //Store statistics
    /**
     * @MongoDB\Int
     */
    public $impressions;
    /**
     * @MongoDB\Int
     */
    public $clicks;
    /**
     * @MongoDB\Int
     */
    public $interactions; 
    

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
     * Set userFullName
     *
     * @param string $userFullName
     * @return self
     */
    public function setUserFullName($userFullName)
    {
        $this->userFullName = $userFullName;
        return $this;
    }

    /**
     * Get userFullName
     *
     * @return string $userFullName
     */
    public function getUserFullName()
    {
        return $this->userFullName;
    }

    /**
     * Set userJobTitle
     *
     * @param string $userJobTitle
     * @return self
     */
    public function setUserJobTitle($userJobTitle)
    {
        $this->userJobTitle = $userJobTitle;
        return $this;
    }

    /**
     * Get userJobTitle
     *
     * @return string $userJobTitle
     */
    public function getUserJobTitle()
    {
        return $this->userJobTitle;
    }

    /**
     * Set type
     *
     * @param int $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return int $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set filter
     *
     * @param string $filter
     * @return self
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * Get filter
     *
     * @return string $filter
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Set action
     *
     * @param string $action
     * @return self
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Get action
     *
     * @return string $action
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set likesCounter
     *
     * @param int $likesCounter
     * @return self
     */
    public function setLikesCounter($likesCounter)
    {
        $this->likesCounter = $likesCounter;
        return $this;
    }

    /**
     * Get likesCounter
     *
     * @return int $likesCounter
     */
    public function getLikesCounter()
    {
        return $this->likesCounter;
    }

    /**
     * Set commentsCounter
     *
     * @param int $commentsCounter
     * @return self
     */
    public function setCommentsCounter($commentsCounter)
    {
        $this->commentsCounter = $commentsCounter;
        return $this;
    }

    /**
     * Get commentsCounter
     *
     * @return int $commentsCounter
     */
    public function getCommentsCounter()
    {
        return $this->commentsCounter;
    }

    /**
     * Set liked
     *
     * @param collection $liked
     * @return self
     */
    public function setLiked($liked)
    {
        $this->liked = $liked;
        return $this;
    }

    /**
     * Get liked
     *
     * @return collection $liked
     */
    public function getLiked()
    {
        return $this->liked;
    }

    /**
     * Set updateID
     *
     * @param string $updateID
     * @return self
     */
    public function setUpdateID($updateID)
    {
        $this->updateID = $updateID;
        return $this;
    }

    /**
     * Get updateID
     *
     * @return string $updateID
     */
    public function getUpdateID()
    {
        return $this->updateID;
    }

    /**
     * Set isLike
     *
     * @param boolean $isLike
     * @return self
     */
    public function setIsLike($isLike)
    {
        $this->isLike = $isLike;
        return $this;
    }

    /**
     * Get isLike
     *
     * @return boolean $isLike
     */
    public function getIsLike()
    {
        return $this->isLike;
    }

    /**
     * Set like
     *
     * @param boolean $like
     * @return self
     */
    public function setLike($like)
    {
        $this->like = $like;
        return $this;
    }

    /**
     * Get like
     *
     * @return boolean $like
     */
    public function getLike()
    {
        return $this->like;
    }

    /**
     * Set likeUserID
     *
     * @param string $likeUserID
     * @return self
     */
    public function setLikeUserID($likeUserID)
    {
        $this->likeUserID = $likeUserID;
        return $this;
    }

    /**
     * Get likeUserID
     *
     * @return string $likeUserID
     */
    public function getLikeUserID()
    {
        return $this->likeUserID;
    }

    /**
     * Set likeUsername
     *
     * @param string $likeUsername
     * @return self
     */
    public function setLikeUsername($likeUsername)
    {
        $this->likeUsername = $likeUsername;
        return $this;
    }

    /**
     * Get likeUsername
     *
     * @return string $likeUsername
     */
    public function getLikeUsername()
    {
        return $this->likeUsername;
    }

    /**
     * Set likeUserFullName
     *
     * @param string $likeUserFullName
     * @return self
     */
    public function setLikeUserFullName($likeUserFullName)
    {
        $this->likeUserFullName = $likeUserFullName;
        return $this;
    }

    /**
     * Get likeUserFullName
     *
     * @return string $likeUserFullName
     */
    public function getLikeUserFullName()
    {
        return $this->likeUserFullName;
    }

    /**
     * Set likeUserAvatar
     *
     * @param string $likeUserAvatar
     * @return self
     */
    public function setLikeUserAvatar($likeUserAvatar)
    {
        $this->likeUserAvatar = $likeUserAvatar;
        return $this;
    }

    /**
     * Get likeUserAvatar
     *
     * @return string $likeUserAvatar
     */
    public function getLikeUserAvatar()
    {
        return $this->likeUserAvatar;
    }

    /**
     * Set inTouchID
     *
     * @param string $inTouchID
     * @return self
     */
    public function setInTouchID($inTouchID)
    {
        $this->inTouchID = $inTouchID;
        return $this;
    }

    /**
     * Get inTouchID
     *
     * @return string $inTouchID
     */
    public function getInTouchID()
    {
        return $this->inTouchID;
    }

    /**
     * Set inTouchUsername
     *
     * @param string $inTouchUsername
     * @return self
     */
    public function setInTouchUsername($inTouchUsername)
    {
        $this->inTouchUsername = $inTouchUsername;
        return $this;
    }

    /**
     * Get inTouchUsername
     *
     * @return string $inTouchUsername
     */
    public function getInTouchUsername()
    {
        return $this->inTouchUsername;
    }

    /**
     * Set inTouchAvatar
     *
     * @param string $inTouchAvatar
     * @return self
     */
    public function setInTouchAvatar($inTouchAvatar)
    {
        $this->inTouchAvatar = $inTouchAvatar;
        return $this;
    }

    /**
     * Get inTouchAvatar
     *
     * @return string $inTouchAvatar
     */
    public function getInTouchAvatar()
    {
        return $this->inTouchAvatar;
    }

    /**
     * Set inTouchFullName
     *
     * @param string $inTouchFullName
     * @return self
     */
    public function setInTouchFullName($inTouchFullName)
    {
        $this->inTouchFullName = $inTouchFullName;
        return $this;
    }

    /**
     * Get inTouchFullName
     *
     * @return string $inTouchFullName
     */
    public function getInTouchFullName()
    {
        return $this->inTouchFullName;
    }

    /**
     * Set inTouchJobTitle
     *
     * @param string $inTouchJobTitle
     * @return self
     */
    public function setInTouchJobTitle($inTouchJobTitle)
    {
        $this->inTouchJobTitle = $inTouchJobTitle;
        return $this;
    }

    /**
     * Get inTouchJobTitle
     *
     * @return string $inTouchJobTitle
     */
    public function getInTouchJobTitle()
    {
        return $this->inTouchJobTitle;
    }

    /**
     * Set inTouchCompany
     *
     * @param string $inTouchCompany
     * @return self
     */
    public function setInTouchCompany($inTouchCompany)
    {
        $this->inTouchCompany = $inTouchCompany;
        return $this;
    }

    /**
     * Get inTouchCompany
     *
     * @return string $inTouchCompany
     */
    public function getInTouchCompany()
    {
        return $this->inTouchCompany;
    }

    /**
     * Set newsID
     *
     * @param string $newsID
     * @return self
     */
    public function setNewsID($newsID)
    {
        $this->newsID = $newsID;
        return $this;
    }

    /**
     * Get newsID
     *
     * @return string $newsID
     */
    public function getNewsID()
    {
        return $this->newsID;
    }

    /**
     * Set newsTitle
     *
     * @param string $newsTitle
     * @return self
     */
    public function setNewsTitle($newsTitle)
    {
        $this->newsTitle = $newsTitle;
        return $this;
    }

    /**
     * Get newsTitle
     *
     * @return string $newsTitle
     */
    public function getNewsTitle()
    {
        return $this->newsTitle;
    }

    /**
     * Set newsSummary
     *
     * @param string $newsSummary
     * @return self
     */
    public function setNewsSummary($newsSummary)
    {
        $this->newsSummary = $newsSummary;
        return $this;
    }

    /**
     * Get newsSummary
     *
     * @return string $newsSummary
     */
    public function getNewsSummary()
    {
        return $this->newsSummary;
    }

    /**
     * Set newsImage
     *
     * @param string $newsImage
     * @return self
     */
    public function setNewsImage($newsImage)
    {
        $this->newsImage = $newsImage;
        return $this;
    }

    /**
     * Get newsImage
     *
     * @return string $newsImage
     */
    public function getNewsImage()
    {
        return $this->newsImage;
    }

    /**
     * Set newsSource
     *
     * @param string $newsSource
     * @return self
     */
    public function setNewsSource($newsSource)
    {
        $this->newsSource = $newsSource;
        return $this;
    }

    /**
     * Get newsSource
     *
     * @return string $newsSource
     */
    public function getNewsSource()
    {
        return $this->newsSource;
    }

    /**
     * Set newsSourceURL
     *
     * @param string $newsSourceURL
     * @return self
     */
    public function setNewsSourceURL($newsSourceURL)
    {
        $this->newsSourceURL = $newsSourceURL;
        return $this;
    }

    /**
     * Get newsSourceURL
     *
     * @return string $newsSourceURL
     */
    public function getNewsSourceURL()
    {
        return $this->newsSourceURL;
    }

    /**
     * Set newsURL
     *
     * @param string $newsURL
     * @return self
     */
    public function setNewsURL($newsURL)
    {
        $this->newsURL = $newsURL;
        return $this;
    }

    /**
     * Get newsURL
     *
     * @return string $newsURL
     */
    public function getNewsURL()
    {
        return $this->newsURL;
    }

    /**
     * Set newsDate
     *
     * @param string $newsDate
     * @return self
     */
    public function setNewsDate($newsDate)
    {
        $this->newsDate = $newsDate;
        return $this;
    }

    /**
     * Get newsDate
     *
     * @return string $newsDate
     */
    public function getNewsDate()
    {
        return $this->newsDate;
    }

    /**
     * Set companyID
     *
     * @param string $companyID
     * @return self
     */
    public function setCompanyID($companyID)
    {
        $this->companyID = $companyID;
        return $this;
    }

    /**
     * Get companyID
     *
     * @return string $companyID
     */
    public function getCompanyID()
    {
        return $this->companyID;
    }

    /**
     * Set companyUsername
     *
     * @param string $companyUsername
     * @return self
     */
    public function setCompanyUsername($companyUsername)
    {
        $this->companyUsername = $companyUsername;
        return $this;
    }

    /**
     * Get companyUsername
     *
     * @return string $companyUsername
     */
    public function getCompanyUsername()
    {
        return $this->companyUsername;
    }

    /**
     * Set companyAvatar
     *
     * @param string $companyAvatar
     * @return self
     */
    public function setCompanyAvatar($companyAvatar)
    {
        $this->companyAvatar = $companyAvatar;
        return $this;
    }

    /**
     * Get companyAvatar
     *
     * @return string $companyAvatar
     */
    public function getCompanyAvatar()
    {
        return $this->companyAvatar;
    }

    /**
     * Set companyFullName
     *
     * @param string $companyFullName
     * @return self
     */
    public function setCompanyFullName($companyFullName)
    {
        $this->companyFullName = $companyFullName;
        return $this;
    }

    /**
     * Get companyFullName
     *
     * @return string $companyFullName
     */
    public function getCompanyFullName()
    {
        return $this->companyFullName;
    }

    /**
     * Set postText
     *
     * @param string $postText
     * @return self
     */
    public function setPostText($postText)
    {
        $this->postText = $postText;
        return $this;
    }

    /**
     * Get postText
     *
     * @return string $postText
     */
    public function getPostText()
    {
        return $this->postText;
    }

    /**
     * Set postImage
     *
     * @param string $postImage
     * @return self
     */
    public function setPostImage($postImage)
    {
        $this->postImage = $postImage;
        return $this;
    }

    /**
     * Get postImage
     *
     * @return string $postImage
     */
    public function getPostImage()
    {
        return $this->postImage;
    }

    /**
     * Set postYoutube
     *
     * @param string $postYoutube
     * @return self
     */
    public function setPostYoutube($postYoutube)
    {
        $this->postYoutube = $postYoutube;
        return $this;
    }

    /**
     * Get postYoutube
     *
     * @return string $postYoutube
     */
    public function getPostYoutube()
    {
        return $this->postYoutube;
    }

    /**
     * Set postVimeo
     *
     * @param string $postVimeo
     * @return self
     */
    public function setPostVimeo($postVimeo)
    {
        $this->postVimeo = $postVimeo;
        return $this;
    }

    /**
     * Get postVimeo
     *
     * @return string $postVimeo
     */
    public function getPostVimeo()
    {
        return $this->postVimeo;
    }

    /**
     * Set eventID
     *
     * @param string $eventID
     * @return self
     */
    public function setEventID($eventID)
    {
        $this->eventID = $eventID;
        return $this;
    }

    /**
     * Get eventID
     *
     * @return string $eventID
     */
    public function getEventID()
    {
        return $this->eventID;
    }

    /**
     * Set eventUsername
     *
     * @param string $eventUsername
     * @return self
     */
    public function setEventUsername($eventUsername)
    {
        $this->eventUsername = $eventUsername;
        return $this;
    }

    /**
     * Get eventUsername
     *
     * @return string $eventUsername
     */
    public function getEventUsername()
    {
        return $this->eventUsername;
    }

    /**
     * Set eventFullName
     *
     * @param string $eventFullName
     * @return self
     */
    public function setEventFullName($eventFullName)
    {
        $this->eventFullName = $eventFullName;
        return $this;
    }

    /**
     * Get eventFullName
     *
     * @return string $eventFullName
     */
    public function getEventFullName()
    {
        return $this->eventFullName;
    }

    /**
     * Set eventText
     *
     * @param string $eventText
     * @return self
     */
    public function setEventText($eventText)
    {
        $this->eventText = $eventText;
        return $this;
    }

    /**
     * Get eventText
     *
     * @return string $eventText
     */
    public function getEventText()
    {
        return $this->eventText;
    }

    /**
     * Set eventImg
     *
     * @param string $eventImg
     * @return self
     */
    public function setEventImg($eventImg)
    {
        $this->eventImg = $eventImg;
        return $this;
    }

    /**
     * Get eventImg
     *
     * @return string $eventImg
     */
    public function getEventImg()
    {
        return $this->eventImg;
    }

    /**
     * Set eventLink
     *
     * @param string $eventLink
     * @return self
     */
    public function setEventLink($eventLink)
    {
        $this->eventLink = $eventLink;
        return $this;
    }

    /**
     * Get eventLink
     *
     * @return string $eventLink
     */
    public function getEventLink()
    {
        return $this->eventLink;
    }

    /**
     * Set eventLinkRegister
     *
     * @param string $eventLinkRegister
     * @return self
     */
    public function setEventLinkRegister($eventLinkRegister)
    {
        $this->eventLinkRegister = $eventLinkRegister;
        return $this;
    }

    /**
     * Get eventLinkRegister
     *
     * @return string $eventLinkRegister
     */
    public function getEventLinkRegister()
    {
        return $this->eventLinkRegister;
    }

    /**
     * Set eventCity
     *
     * @param string $eventCity
     * @return self
     */
    public function setEventCity($eventCity)
    {
        $this->eventCity = $eventCity;
        return $this;
    }

    /**
     * Get eventCity
     *
     * @return string $eventCity
     */
    public function getEventCity()
    {
        return $this->eventCity;
    }

    /**
     * Set eventDate
     *
     * @param string $eventDate
     * @return self
     */
    public function setEventDate($eventDate)
    {
        $this->eventDate = $eventDate;
        return $this;
    }

    /**
     * Get eventDate
     *
     * @return string $eventDate
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * Set comments
     *
     * @param collection $comments
     * @return self
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * Get comments
     *
     * @return collection $comments
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set isComment
     *
     * @param boolean $isComment
     * @return self
     */
    public function setIsComment($isComment)
    {
        $this->isComment = $isComment;
        return $this;
    }

    /**
     * Get isComment
     *
     * @return boolean $isComment
     */
    public function getIsComment()
    {
        return $this->isComment;
    }

    /**
     * Set sourceID
     *
     * @param string $sourceID
     * @return self
     */
    public function setSourceID($sourceID)
    {
        $this->sourceID = $sourceID;
        return $this;
    }

    /**
     * Get sourceID
     *
     * @return string $sourceID
     */
    public function getSourceID()
    {
        return $this->sourceID;
    }

    /**
     * Set companyFollowers
     *
     * @param int $companyFollowers
     * @return self
     */
    public function setCompanyFollowers($companyFollowers)
    {
        $this->companyFollowers = $companyFollowers;
        return $this;
    }

    /**
     * Get companyFollowers
     *
     * @return int $companyFollowers
     */
    public function getCompanyFollowers()
    {
        return $this->companyFollowers;
    }

    /**
     * Set sharedUsername
     *
     * @param string $sharedUsername
     * @return self
     */
    public function setSharedUsername($sharedUsername)
    {
        $this->sharedUsername = $sharedUsername;
        return $this;
    }

    /**
     * Get sharedUsername
     *
     * @return string $sharedUsername
     */
    public function getSharedUsername()
    {
        return $this->sharedUsername;
    }

    /**
     * Set sharedUserAvatar
     *
     * @param string $sharedUserAvatar
     * @return self
     */
    public function setSharedUserAvatar($sharedUserAvatar)
    {
        $this->sharedUserAvatar = $sharedUserAvatar;
        return $this;
    }

    /**
     * Get sharedUserAvatar
     *
     * @return string $sharedUserAvatar
     */
    public function getSharedUserAvatar()
    {
        return $this->sharedUserAvatar;
    }

    /**
     * Set sharedUserFullName
     *
     * @param string $sharedUserFullName
     * @return self
     */
    public function setSharedUserFullName($sharedUserFullName)
    {
        $this->sharedUserFullName = $sharedUserFullName;
        return $this;
    }

    /**
     * Get sharedUserFullName
     *
     * @return string $sharedUserFullName
     */
    public function getSharedUserFullName()
    {
        return $this->sharedUserFullName;
    }

    /**
     * Set sharedUserJobTitle
     *
     * @param string $sharedUserJobTitle
     * @return self
     */
    public function setSharedUserJobTitle($sharedUserJobTitle)
    {
        $this->sharedUserJobTitle = $sharedUserJobTitle;
        return $this;
    }

    /**
     * Get sharedUserJobTitle
     *
     * @return string $sharedUserJobTitle
     */
    public function getSharedUserJobTitle()
    {
        return $this->sharedUserJobTitle;
    }

    /**
     * Set sharedUserCompany
     *
     * @param string $sharedUserCompany
     * @return self
     */
    public function setSharedUserCompany($sharedUserCompany)
    {
        $this->sharedUserCompany = $sharedUserCompany;
        return $this;
    }

    /**
     * Get sharedUserCompany
     *
     * @return string $sharedUserCompany
     */
    public function getSharedUserCompany()
    {
        return $this->sharedUserCompany;
    }

    /**
     * Set companyType
     *
     * @param string $companyType
     * @return self
     */
    public function setCompanyType($companyType)
    {
        $this->companyType = $companyType;
        return $this;
    }

    /**
     * Get companyType
     *
     * @return string $companyType
     */
    public function getCompanyType()
    {
        return $this->companyType;
    }

    /**
     * Set sharedCompanyUsername
     *
     * @param string $sharedCompanyUsername
     * @return self
     */
    public function setSharedCompanyUsername($sharedCompanyUsername)
    {
        $this->sharedCompanyUsername = $sharedCompanyUsername;
        return $this;
    }

    /**
     * Get sharedCompanyUsername
     *
     * @return string $sharedCompanyUsername
     */
    public function getSharedCompanyUsername()
    {
        return $this->sharedCompanyUsername;
    }

    /**
     * Set sharedCompanyAvatar
     *
     * @param string $sharedCompanyAvatar
     * @return self
     */
    public function setSharedCompanyAvatar($sharedCompanyAvatar)
    {
        $this->sharedCompanyAvatar = $sharedCompanyAvatar;
        return $this;
    }

    /**
     * Get sharedCompanyAvatar
     *
     * @return string $sharedCompanyAvatar
     */
    public function getSharedCompanyAvatar()
    {
        return $this->sharedCompanyAvatar;
    }

    /**
     * Set sharedCompanyName
     *
     * @param string $sharedCompanyName
     * @return self
     */
    public function setSharedCompanyName($sharedCompanyName)
    {
        $this->sharedCompanyName = $sharedCompanyName;
        return $this;
    }

    /**
     * Get sharedCompanyName
     *
     * @return string $sharedCompanyName
     */
    public function getSharedCompanyName()
    {
        return $this->sharedCompanyName;
    }

    /**
     * Set sharedCompanyFollowers
     *
     * @param string $sharedCompanyFollowers
     * @return self
     */
    public function setSharedCompanyFollowers($sharedCompanyFollowers)
    {
        $this->sharedCompanyFollowers = $sharedCompanyFollowers;
        return $this;
    }

    /**
     * Get sharedCompanyFollowers
     *
     * @return string $sharedCompanyFollowers
     */
    public function getSharedCompanyFollowers()
    {
        return $this->sharedCompanyFollowers;
    }


    /**
     * Set sharedCompanyType
     *
     * @param string $sharedCompanyType
     * @return self
     */
    public function setSharedCompanyType($sharedCompanyType)
    {
        $this->sharedCompanyType = $sharedCompanyType;
        return $this;
    }

    /**
     * Get sharedCompanyType
     *
     * @return string $sharedCompanyType
     */
    public function getSharedCompanyType()
    {
        return $this->sharedCompanyType;
    }
    
     /**
     * Set impressions
     *
     * @param Int $impressions
     * @return self
     */
    public function setImpressions($impressions)
    {
        $this->impressions = $impressions;
        return $this;
    }

    /**
     * Get impressions
     *
     * @return Int $impressions
     */
    public function getImpressions()
    {
        return $this->impressions;
    }
    
     /**
     * Set clicks
     *
     * @param Int $clicks
     * @return self
     */
    public function setClicks($clicks)
    {
        $this->clicks = $clicks;
        return $this;
    }

    /**
     * Get clicks
     *
     * @return Int $clicks
     */
    public function getClicks()
    {
        return $this->clicks;
    }
    
     /**
     * Set interactions
     *
     * @param Int $interactions
     * @return self
     */
    public function setInteractions($interactions)
    {
        $this->interactions = $interactions;
        return $this;
    }

    /**
     * Get interactions
     *
     * @return collection $interactions
     */
    public function getInteractions()
    {
        return $this->interactions;
    }
}
