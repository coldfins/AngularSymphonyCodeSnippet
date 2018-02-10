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
class ServiceSuggestions {

    /**
     * @MongoDB\Id(strategy="auto")
     */
    public $id;

    /**
     * @MongoDB\String
     */
    public $service;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $search;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $suggestedBy;

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
     * Set service
     *
     * @param string $service
     * @return self
     */
    public function setService($service) {
        $this->service = $service;
        return $this;
    }

    /**
     * Get service
     *
     * @return string $service
     */
    public function getService() {
        return $this->service;
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
     * Set suggestedBy
     *
     * @param collection $suggestedBy
     * @return self
     */
    public function setSuggestedBy($suggestedBy) {
        $this->suggestedBy = $suggestedBy;
        return $this;
    }

    /**
     * Get suggestedBy
     *
     * @return collection $suggestedBy
     */
    public function getSuggestedBy() {
        return $this->suggestedBy;
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
