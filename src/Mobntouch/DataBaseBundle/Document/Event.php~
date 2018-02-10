<?php
/**
 * Created by PhpStorm.
 * User: josepmarti
 * Date: 27/10/14
 * Time: 13:02
 */

namespace Mobntouch\DataBaseBundle\Document;

use FOS\UserBundle\Model\User as BaseUser;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;




/**
 * @MongoDB\Document
 */
class Event {

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
    public $img;

    /**
     * @MongoDB\String
     */
    public $link;

    /**
     * @MongoDB\String
     */
    public $linkRegister;
    /**
     * @MongoDB\String
     */
    public $text;

    /**
     * @MongoDB\String
     */
    public $city;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $attendees;

    /**
     * @MongoDB\String
     */
    public $date;

    /**
     * @MongoDB\String
     */
    private $updateDate;



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
     * Set img
     *
     * @param string $img
     * @return self
     */
    public function setImg($img)
    {
        $this->img = $img;
        return $this;
    }

    /**
     * Get img
     *
     * @return string $img
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return self
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * Get link
     *
     * @return string $link
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set linkRegister
     *
     * @param string $linkRegister
     * @return self
     */
    public function setLinkRegister($linkRegister)
    {
        $this->linkRegister = $linkRegister;
        return $this;
    }

    /**
     * Get linkRegister
     *
     * @return string $linkRegister
     */
    public function getLinkRegister()
    {
        return $this->linkRegister;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return self
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Get text
     *
     * @return string $text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return self
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get city
     *
     * @return string $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set attendees
     *
     * @param collection $attendees
     * @return self
     */
    public function setAttendees($attendees)
    {
        $this->attendees = $attendees;
        return $this;
    }

    /**
     * Get attendees
     *
     * @return collection $attendees
     */
    public function getAttendees()
    {
        return $this->attendees;
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
}
