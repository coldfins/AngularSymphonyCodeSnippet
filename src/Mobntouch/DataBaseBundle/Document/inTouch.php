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
class inTouch {

    /**
     * @MongoDB\Id(strategy="auto")
     */
    public $id;

    /**
     * @MongoDB\String
     */
    public $userID1;

    /**
     * @MongoDB\String
     */
    public $userID2;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $user1;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $user2;

    /**
     * @MongoDB\String
     */
    public $date;

    /**
     * @MongoDB\Int
     */
    public $status;


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
     * Set userID1
     *
     * @param string $userID1
     * @return self
     */
    public function setUserID1($userID1)
    {
        $this->userID1 = $userID1;
        return $this;
    }

    /**
     * Get userID1
     *
     * @return string $userID1
     */
    public function getUserID1()
    {
        return $this->userID1;
    }

    /**
     * Set userID2
     *
     * @param string $userID2
     * @return self
     */
    public function setUserID2($userID2)
    {
        $this->userID2 = $userID2;
        return $this;
    }

    /**
     * Get userID2
     *
     * @return string $userID2
     */
    public function getUserID2()
    {
        return $this->userID2;
    }

    /**
     * Set user1
     *
     * @param collection $user1
     * @return self
     */
    public function setUser1($user1)
    {
        $this->user1 = $user1;
        return $this;
    }

    /**
     * Get user1
     *
     * @return collection $user1
     */
    public function getUser1()
    {
        return $this->user1;
    }

    /**
     * Set user2
     *
     * @param collection $user2
     * @return self
     */
    public function setUser2($user2)
    {
        $this->user2 = $user2;
        return $this;
    }

    /**
     * Get user2
     *
     * @return collection $user2
     */
    public function getUser2()
    {
        return $this->user2;
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
     * Set status
     *
     * @param int $status
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
     * @return int $status
     */
    public function getStatus()
    {
        return $this->status;
    }
}
