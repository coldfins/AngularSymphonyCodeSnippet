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
class CompanySearch {

    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    public $companyID;

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
     * @MongoDB\String
     */
    public $size;

    /**
     * @MongoDB\String
     */
    public $companyType;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $companySubType;

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
     * Set size
     *
     * @param string $size
     * @return self
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Get size
     *
     * @return string $size
     */
    public function getSize()
    {
        return $this->size;
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
     * Set companySubType
     *
     * @param collection $companySubType
     * @return self
     */
    public function setCompanySubType($companySubType)
    {
        $this->companySubType = $companySubType;
        return $this;
    }

    /**
     * Get companySubType
     *
     * @return collection $companySubType
     */
    public function getCompanySubType()
    {
        return $this->companySubType;
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
}
