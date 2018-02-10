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
#use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
#use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;



/**
 * @MongoDB\Document
 */
//class User implements UserInterface, EquatableInterface {
class UsersActivity extends BaseUser {

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
    public $loginTime;

    /**
     * @MongoDB\String
     */
    public $updatedTime;

    /**
     * @MongoDB\int
     */
    public $session;

    /**
     * @MongoDB\String
     */
    public $sessionType;
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
     * Set loginTime
     *
     * @param string $loginTime
     * @return self
     */
    public function setLoginTime($loginTime)
    {
        $this->loginTime = $loginTime;
        return $this;
    }

    /**
     * Get loginTime
     *
     * @return string $loginTime
     */
    public function getLoginTime()
    {
        return $this->loginTime;
    }

    /**
     * Set updatedTime
     *
     * @param string $updatedTime
     * @return self
     */
    public function setUpdatedTime($updatedTime)
    {
        $this->updatedTime = $updatedTime;
        return $this;
    }

    /**
     * Get updatedTime
     *
     * @return string $updatedTime
     */
    public function getUpdatedTime()
    {
        return $this->updatedTime;
    }

    /**
     * Set session
     *
     * @param int $session
     * @return self
     */
    public function setSession($session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string $lastname
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set sessionType
     *
     * @param string $sessionType
     * @return self
     */
    public function setSessionType($sessionType)
    {
        $this->sessionType = $sessionType;
        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getSessionType()
    {
        return $this->sessionType;
    }
 
 }
