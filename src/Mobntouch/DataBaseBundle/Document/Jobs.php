<?php

namespace Mobntouch\DataBaseBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Jobs {

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
    public $jobTitle;

    /**
     * @MongoDB\String
     */
    public $description;

    /**
     * @MongoDB\String
     */
    public $whyUs;

    /**
     * @MongoDB\String
     */
    public $primaryRole;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $otherRoles;

    /**
     * @MongoDB\String
     */
    public $jobType;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $location;

    /**
     * @MongoDB\Boolean
     */
    public $remote;

    /**
     * @MongoDB\int
     */
    public $minSalary;

    /**
     * @MongoDB\int
     */
    public $maxSalary;

    /**
     * @MongoDB\String
     */
    public $currencyCode;

    /**
     * @MongoDB\String
     */
    public $currencySymbol;

    /**
     * @MongoDB\Float
     */
    public $equityMin;

    /**
     * @MongoDB\Float
     */
    public $equityMax;

    /**
     * @MongoDB\Float
     */
    public $equityVest;

    /**
     * @MongoDB\Float
     */
    public $equityCliff;

    /**
     * @MongoDB\String
     */
    public $jobOfferUrl;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $skills;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $createdBy;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $company;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    private $appliedBy;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    private $starredBy;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    private $skippedBy;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $search;

    /**
     * @MongoDB\String
     */
    public $publishStatus;

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
     * Get jobTitle
     *
     * @return string $jobTitle
     */
    public function getJobTitle() {
        return $this->jobTitle;
    }

    /**
     * Set jobTitle
     *
     * @param string $jobTitle
     * @return self
     */
    public function setJobTitle($jobTitle) {
        $this->jobTitle = $jobTitle;
        return $this;
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
     * Get description
     *
     * @return string $description
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return self
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    /**
     * Get whyUs
     *
     * @return string $whyUs
     */
    public function getWhyUs() {
        return $this->whyUs;
    }

    /**
     * Set whyUs
     *
     * @param string $whyUs
     * @return self
     */
    public function setWhyUs($whyUs) {
        $this->whyUs = $whyUs;
        return $this;
    }

    /**
     * Get primaryRole
     *
     * @return string $primaryRole
     */
    public function getPrimaryRole() {
        return $this->primaryRole;
    }

    /**
     * Set primaryRole
     *
     * @param string $primaryRole
     * @return self
     */
    public function setPrimaryRole($primaryRole) {
        $this->primaryRole = $primaryRole;
        return $this;
    }

    /**
     * Get otherRoles
     *
     * @return collection $otherRoles
     */
    public function getOtherRoles() {
        return $this->otherRoles;
    }

    /**
     * Set otherRoles
     *
     * @param collection $otherRoles
     * @return self
     */
    public function setOtherRoles($otherRoles) {
        $this->otherRoles = $otherRoles;
        return $this;
    }

    /**
     * Get jobType
     *
     * @return string $jobType
     */
    public function getJobType() {
        return $this->jobType;
    }

    /**
     * Set jobType
     *
     * @param string $jobType
     * @return self
     */
    public function setJobType($jobType) {
        $this->jobType = $jobType;
        return $this;
    }

    /**
     * Get location
     *
     * @return collection $location
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * Set location
     *
     * @param collection $location
     * @return self
     */
    public function setLocation($location) {
        $this->location = $location;
        return $this;
    }

    /**
     * Get remote
     *
     * @return boolean $remote
     */
    public function getRemote() {
        return $this->remote;
    }

    /**
     * Set remote
     *
     * @param boolean $remote
     * @return self
     */
    public function setRemote($remote) {
        $this->remote = $remote;
        return $this;
    }

    /**
     * Get minSalary
     *
     * @return int $minSalary
     */
    public function getMinSalary() {
        return $this->minSalary;
    }

    /**
     * Set minSalary
     *
     * @param int $minSalary
     * @return self
     */
    public function setMinSalary($minSalary) {
        $this->minSalary = $minSalary;
        return $this;
    }

    /**
     * Get maxSalary
     *
     * @return int $maxSalary
     */
    public function getMaxSalary() {
        return $this->maxSalary;
    }

    /**
     * Set maxSalary
     *
     * @param int $maxSalary
     * @return self
     */
    public function setMaxSalary($maxSalary) {
        $this->maxSalary = $maxSalary;
        return $this;
    }

    /**
     * Get currencyCode
     *
     * @return string $currencyCode
     */
    public function getCurrencyCode() {
        return $this->currencyCode;
    }

    /**
     * Set currencyCode
     *
     * @param string $currencyCode
     * @return self
     */
    public function setCurrencyCode($currencyCode) {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    /**
     * Get currencySymbol
     *
     * @return string $currencySymbol
     */
    public function getCurrencySymbol() {
        return $this->currencySymbol;
    }

    /**
     * Set currencySymbol
     *
     * @param string $currencySymbol
     * @return self
     */
    public function setCurrencySymbol($currencySymbol) {
        $this->currencySymbol = $currencySymbol;
        return $this;
    }

    /**
     * Get equityMin
     *
     * @return float $equityMin
     */
    public function getEquityMin() {
        return $this->equityMin;
    }

    /**
     * Set equityMin
     *
     * @param float $equityMin
     * @return self
     */
    public function setEquityMin($equityMin) {
        $this->equityMin = $equityMin;
        return $this;
    }

    /**
     * Get equityMax
     *
     * @return float $equityMax
     */
    public function getEquityMax() {
        return $this->equityMax;
    }

    /**
     * Set equityMax
     *
     * @param float $equityMax
     * @return self
     */
    public function setEquityMax($equityMax) {
        $this->equityMax = $equityMax;
        return $this;
    }

    /**
     * Get equityVest
     *
     * @return float $equityVest
     */
    public function getEquityVest() {
        return $this->equityVest;
    }

    /**
     * Set equityVest
     *
     * @param float $equityVest
     * @return self
     */
    public function setEquityVest($equityVest) {
        $this->equityVest = $equityVest;
        return $this;
    }

    /**
     * Get equityCliff
     *
     * @return float $equityCliff
     */
    public function getEquityCliff() {
        return $this->equityCliff;
    }

    /**
     * Set equityCliff
     *
     * @param float $equityCliff
     * @return self
     */
    public function setEquityCliff($equityCliff) {
        $this->equityCliff = $equityCliff;
        return $this;
    }

    /**
     * Get jobOfferUrl
     *
     * @return string $jobOfferUrl
     */
    public function getJobOfferUrl() {
        return $this->jobOfferUrl;
    }

    /**
     * Set jobOfferUrl
     *
     * @param string $jobOfferUrl
     * @return self
     */
    public function setJobOfferUrl($jobOfferUrl) {
        $this->jobOfferUrl = $jobOfferUrl;
        return $this;
    }

    /**
     * Get skills
     *
     * @return collection $skills
     */
    public function getSkills() {
        return $this->skills;
    }

    /**
     * Set skills
     *
     * @param collection $skills
     * @return self
     */
    public function setSkills($skills) {
        $this->skills = $skills;
        return $this;
    }

    /**
     * Get createdBy
     *
     * @return collection $createdBy
     */
    public function getCreatedBy() {
        return $this->createdBy;
    }

    /**
     * Set createdBy
     *
     * @param collection $createdBy
     * @return self
     */
    public function setCreatedBy($createdBy) {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * Get company
     *
     * @return collection $company
     */
    public function getCompany() {
        return $this->company;
    }

    /**
     * Set company
     *
     * @param collection $company
     * @return self
     */
    public function setCompany($company) {
        $this->company = $company;
        return $this;
    }

    /**
     * Get appliedBy
     *
     * @return collection $appliedBy
     */
    public function getAppliedBy() {
        return $this->appliedBy;
    }

    /**
     * Set appliedBy
     *
     * @param collection $appliedBy
     * @return self
     */
    public function setAppliedBy($appliedBy) {
        $this->appliedBy = $appliedBy;
        return $this;
    }

    /**
     * Get starredBy
     *
     * @return collection $starredBy
     */
    public function getStarredBy() {
        return $this->starredBy;
    }

    /**
     * Set starredBy
     *
     * @param collection $starredBy
     * @return self
     */
    public function setStarredBy($starredBy) {
        $this->starredBy = $starredBy;
        return $this;
    }

    /**
     * Get skippedBy
     *
     * @return collection $skippedBy
     */
    public function getSkippedBy() {
        return $this->skippedBy;
    }

    /**
     * Set skippedBy
     *
     * @param collection $skippedBy
     * @return self
     */
    public function setSkippedBy($skippedBy) {
        $this->skippedBy = $skippedBy;
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
     * Get publishStatus
     *
     * @return string $publishStatus
     */
    public function getPublishStatus() {
        return $this->publishStatus;
    }

    /**
     * Set publishStatus
     *
     * @param string $publishStatus
     * @return self
     */
    public function setPublishStatus($publishStatus) {
        $this->publishStatus = $publishStatus;
        return $this;
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
