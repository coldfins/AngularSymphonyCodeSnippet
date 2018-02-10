<?php

namespace Mobntouch\DataBaseBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Qa {

    /**
     * @MongoDB\Id(strategy="auto")
     */
    public $id;

    /**
     * @MongoDB\String
     */
    public $slug;

    /**
     * @MongoDB\String
     */
    public $title;

    /**
     * @MongoDB\String
     */
    public $details;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $tags;

    /**
     * @MongoDB\Boolean
     */
    public $isAnonymously;

    /**
     * @MongoDB\Boolean
     */
    public $sendNotification;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $askedBy;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $answers;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $savedBy;

    /**
     * @MongoDB\Int
     */
    public $upVotes;

    /**
     * @MongoDB\Int
     */
    public $downVotes;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $history;

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
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $search;

    /**
     * @MongoDB\String
     */
    public $createDate;

    /**
     * @MongoDB\String
     */
    public $updateDate;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get slug
     *
     * @return string $slug
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return self
     */
    public function setSlug($slug) {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return self
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * Get details
     *
     * @return string $details
     */
    public function getDetails() {
        return $this->details;
    }

    /**
     * Set details
     *
     * @param string $details
     * @return self
     */
    public function setDetails($details) {
        $this->details = $details;
        return $this;
    }

    /**
     * Get tags
     *
     * @return collection $tags
     */
    public function getTags() {
        return $this->tags;
    }

    /**
     * Set tags
     *
     * @param collection $tags
     * @return self
     */
    public function setTags($tags) {
        $this->tags = $tags;
        return $this;
    }

    /**
     * Get isAnonymously
     *
     * @return boolean $isAnonymously
     */
    public function getIsAnonymously() {
        return $this->isAnonymously;
    }

    /**
     * Set isAnonymously
     *
     * @param boolean $isAnonymously
     * @return self
     */
    public function setIsAnonymously($isAnonymously) {
        $this->isAnonymously = $isAnonymously;
        return $this;
    }

    /**
     * Get sendNotification
     *
     * @return boolean $sendNotification
     */
    public function getSendNotification() {
        return $this->sendNotification;
    }

    /**
     * Set sendNotification
     *
     * @param boolean $sendNotification
     * @return self
     */
    public function setSendNotification($sendNotification) {
        $this->sendNotification = $sendNotification;
        return $this;
    }

    /**
     * Get askedBy
     *
     * @return collection $askedBy
     */
    public function getAskedBy() {
        return $this->askedBy;
    }

    /**
     * Set askedBy
     *
     * @param collection $askedBy
     * @return self
     */
    public function setAskedBy($askedBy) {
        $this->askedBy = $askedBy;
        return $this;
    }

    /**
     * Get answers
     *
     * @return collection $answers
     */
    public function getAnswers() {
        return $this->answers;
    }

    /**
     * Set answers
     *
     * @param collection $answers
     * @return self
     */
    public function setAnswers($answers) {
        $this->answers = $answers;
        return $this;
    }

    /**
     * Get savedBy
     *
     * @return collection $savedBy
     */
    public function getSavedBy() {
        return $this->savedBy;
    }

    /**
     * Set savedBy
     *
     * @param collection $savedBy
     * @return self
     */
    public function setSavedBy($savedBy) {
        $this->savedBy = $savedBy;
        return $this;
    }

    /**
     * Get upVotes
     *
     * @return int $upVotes
     */
    public function getUpVotes() {
        return $this->upVotes;
    }

    /**
     * Set upVotes
     *
     * @param int $upVotes
     * @return self
     */
    public function setUpVotes($upVotes) {
        $this->answers = $upVotes;
        return $this;
    }

    /**
     * Get downVotes
     *
     * @return int $downVotes
     */
    public function getDownVotes() {
        return $this->downVotes;
    }

    /**
     * Set downVotes
     *
     * @param int $downVotes
     * @return self
     */
    public function setDownVotes($downVotes) {
        $this->downVotes = $downVotes;
        return $this;
    }

    /**
     * Set history
     *
     * @param collection $history
     * @return self
     */
    public function setHistory($history) {
        $this->history = $history;
        return $this;
    }

    /**
     * Get history
     *
     * @return collection $history
     */
    public function getHistory() {
        return $this->history;
    }

    /**
     * Set pageViews
     *
     * @param int $pageViews
     * @return self
     */
    public function setPageViews($pageViews) {
        $this->pageViews = $pageViews;
        return $this;
    }

    /**
     * Get pageViews
     *
     * @return int $pageViews
     */
    public function getPageViews() {
        return $this->pageViews;
    }

    /**
     * Set uniquePageViews
     *
     * @param collection $uniquePageViews
     * @return self
     */
    public function setUniquePageViews($uniquePageViews) {
        $this->uniquePageViews = $uniquePageViews;
        return $this;
    }

    /**
     * Get uniquePageViews
     *
     * @return collection $uniquePageViews
     */
    public function getUniquePageViews() {
        return $this->uniquePageViews;
    }

    /**
     * Set dailyUniquePageViews
     *
     * @param collection $dailyUniquePageViews
     * @return self
     */
    public function setDailyUniquePageViews($dailyUniquePageViews) {
        $this->dailyUniquePageViews = $dailyUniquePageViews;
        return $this;
    }

    /**
     * Get dailyUniquePageViews
     *
     * @return collection $dailyUniquePageViews
     */
    public function getDailyUniquePageViews() {
        return $this->dailyUniquePageViews;
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
     * Get createDate
     *
     * @return string $createDate
     */
    public function getCreateDate() {
        return $this->createDate;
    }

    /**
     * Set createDate
     *
     * @param string $createDate
     * @return self
     */
    public function setCreateDate($createDate) {
        $this->createDate = $createDate;
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

}
