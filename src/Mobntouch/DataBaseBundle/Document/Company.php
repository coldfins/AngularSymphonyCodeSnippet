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
class Company {

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
    public $shortDescription;

    /**
     * @MongoDB\String
     */
    public $description;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $video;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $images;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $product;

    /**
     * @MongoDB\String
     */
    public $size;

    /**
     * @MongoDB\String
     */
    public $whyUs;

    /**
     * @MongoDB\Boolean
     */
    public $validDescriptionBox;

    /**
     * @MongoDB\String
     */
    public $city;

    /**
     * @MongoDB\String
     */
    public $country;

    /**
     * @MongoDB\String
     */
    public $basedCountry;

    /**
     * @MongoDB\String
     */
    public $formatedAddress;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $markets;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $companyTypes;

    /**
     * @MongoDB\String
     */
    public $companyType;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $companySubType;

    /**
     * @MongoDB\String
     */
    public $otherCompanySubType;

    /**
     * @MongoDB\String
     */
    public $website;

    /**
     * @MongoDB\String
     */
    public $twitter;

    /**
     * @MongoDB\String
     */
    public $facebook;

    /**
     * @MongoDB\String
     */
    public $instagram;

    /**
     * @MongoDB\String
     */
    public $linkedIn;

    /**
     * @MongoDB\String
     */
    public $blog;

    /**
     * @MongoDB\String
     */
    public $foundedin;

    /**
     * @MongoDB\String
     */
    public $founders;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $references;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $events;

    /**
     * @MongoDB\String
     */
    public $avatar;

    /**
     * @MongoDB\String
     */
    public $cover;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $settings;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $administrators;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $employees;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $recruiters;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $followers;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $visitors;

    /**
     * @MongoDB\boolean
     */
    public $isHiring;

    /**
     * @MongoDB\Int
     */
    public $companyPercentage;

    /**
     * @MongoDB\Int
     */
    public $companyPoints;

    /**
     * @MongoDB\Int
     */
    public $companyRank;

    /**
     * @MongoDB\Int
     */
    public $oldCompanyRank;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $search;

    /**
     * @MongoDB\String
     */
    private $updateDate;

    /**
     *
     * @Assert\File(
     * maxSize = "6M",
     * 	 notFoundMessage = "Max 6M"
     *  	)
     */
    //protected $file;

    /**
     * @MongoDB\Float
     */
    public $lat;

    /**
     * @MongoDB\Float
     */
    public $lng;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return self
     */
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    /**
     * Get username
     *
     * @return string $username
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return self
     */
    public function setShortDescription($shortDescription) {
        $this->shortDescription = $shortDescription;
        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string $shortDescription
     */
    public function getShortDescription() {
        return $this->shortDescription;
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
     * Get description
     *
     * @return string $description
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set video
     *
     * @param collection $video
     * @return self
     */
    public function setVideo($video) {
        $this->video = $video;
        return $this;
    }

    /**
     * Get video
     *
     * @return collection $video
     */
    public function getVideo() {
        return $this->video;
    }

    /**
     * Set images
     *
     * @param collection $images
     * @return self
     */
    public function setImages($images) {
        $this->images = $images;
        return $this;
    }

    /**
     * Get images
     *
     * @return collection $images
     */
    public function getImages() {
        return $this->images;
    }

    /**
     * Set product
     *
     * @param collection $product
     * @return self
     */
    public function setProduct($product) {
        $this->product = $product;
        return $this;
    }

    /**
     * Get product
     *
     * @return collection $product
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     * Set size
     *
     * @param string $size
     * @return self
     */
    public function setSize($size) {
        $this->size = $size;
        return $this;
    }

    /**
     * Get size
     *
     * @return string $size
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * Set markets
     *
     * @param string $markets
     * @return self
     */
    public function setMarkets($markets) {
        $this->markets = $markets;
        return $this;
    }

    /**
     * Get markets
     *
     * @return string $markets
     */
    public function getMarkets() {
        return $this->markets;
    }

    /**
     * Set companyTypes
     *
     * @param string $companyTypes
     * @return self
     */
    public function setCompanyTypes($companyTypes) {
        $this->companyTypes = $companyTypes;
        return $this;
    }

    /**
     * Get companyTypes
     *
     * @return string $companyTypes
     */
    public function getCompanyTypes() {
        return $this->companyTypes;
    }

    /**
     * Set companyType
     *
     * @param string $companyType
     * @return self
     */
    public function setCompanyType($companyType) {
        $this->companyType = $companyType;
        return $this;
    }

    /**
     * Get companyType
     *
     * @return string $companyType
     */
    public function getCompanyType() {
        return $this->companyType;
    }

    /**
     * Set companySubType
     *
     * @param collection $companySubType
     * @return self
     */
    public function setCompanySubType($companySubType) {
        $this->companySubType = $companySubType;
        return $this;
    }

    /**
     * Get companySubType
     *
     * @return collection $companySubType
     */
    public function getCompanySubType() {
        return $this->companySubType;
    }

    /**
     * Set otherCompanySubType
     *
     * @param string $otherCompanySubType
     * @return self
     */
    public function setOtherCompanySubType($otherCompanySubType) {
        $this->otherCompanySubType = $otherCompanySubType;
        return $this;
    }

    /**
     * Get otherCompanySubType
     *
     * @return string $otherCompanySubType
     */
    public function getOtherCompanySubType() {
        return $this->otherCompanySubType;
    }

    /**
     * Set references
     *
     * @param collection $references
     * @return self
     */
    public function setReferences($references) {
        $this->references = $references;
        return $this;
    }

    /**
     * Get references
     *
     * @return collection $references
     */
    public function getReferences() {
        return $this->references;
    }

    /**
     * Set events
     *
     * @param collection $events
     * @return self
     */
    public function setEvents($events) {
        $this->events = $events;
        return $this;
    }

    /**
     * Get events
     *
     * @return collection $events
     */
    public function getEvents() {
        return $this->events;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return self
     */
    public function setAvatar($avatar) {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * Get avatar
     *
     * @return string $avatar
     */
    public function getAvatar() {
        return $this->avatar;
    }

    /**
     * Set cover
     *
     * @param string $cover
     * @return self
     */
    public function setCover($cover) {
        $this->cover = $cover;
        return $this;
    }

    /**
     * Get cover
     *
     * @return string $cover
     */
    public function getCover() {
        return $this->cover;
    }

    /**
     * Set settings
     *
     * @param collection $settings
     * @return self
     */
    public function setSettings($settings) {
        $this->settings = $settings;
        return $this;
    }

    /**
     * Get settings
     *
     * @return collection $settings
     */
    public function getSettings() {
        return $this->settings;
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
     * Get whyUs
     *
     * @return string $whyUs
     */
    public function getWhyUs() {
        return $this->whyUs;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return self
     */
    public function setCity($city) {
        $this->city = $city;
        return $this;
    }

    /**
     * Get city
     *
     * @return string $city
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return self
     */
    public function setCountry($country) {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return string $country
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * Set basedCountry
     *
     * @param string $basedCountry
     * @return self
     */
    public function setBasedCountry($basedCountry) {
        $this->basedCountry = $basedCountry;
        return $this;
    }

    /**
     * Get basedCountry
     *
     * @return string $basedCountry
     */
    public function getBasedCountry() {
        return $this->basedCountry;
    }

    /**
     * Set formatedAddress
     *
     * @param string $formatedAddress
     * @return self
     */
    public function setFormatedAddress($formatedAddress) {
        $this->formatedAddress = $formatedAddress;
        return $this;
    }

    /**
     * Get formatedAddress
     *
     * @return string $formatedAddress
     */
    public function getFormatedAddress() {
        return $this->formatedAddress;
    }

    /**
     * Set administrators
     *
     * @param collection $administrators
     * @return self
     */
    public function setAdministrators($administrators) {
        $this->administrators = $administrators;
        return $this;
    }

    /**
     * Get administrators
     *
     * @return collection $administrators
     */
    public function getAdministrators() {
        return $this->administrators;
    }

    /**
     * Set employees
     *
     * @param collection $employees
     * @return self
     */
    public function setEmployees($employees) {
        $this->employees = $employees;
        return $this;
    }

    /**
     * Get employees
     *
     * @return collection $employees
     */
    public function getEmployees() {
        return $this->employees;
    }

    /**
     * Set recruiters
     *
     * @param collection $recruiters
     * @return self
     */
    public function setRecruiters($recruiters) {
        $this->recruiters = $recruiters;
        return $this;
    }

    /**
     * Get recruiters
     *
     * @return collection $recruiters
     */
    public function getRecruiters() {
        return $this->recruiters;
    }

    /**
     * Set followers
     *
     * @param collection $followers
     * @return self
     */
    public function setFollowers($followers) {
        $this->followers = $followers;
        return $this;
    }

    /**
     * Get followers
     *
     * @return collection $followers
     */
    public function getFollowers() {
        return $this->followers;
    }

    /**
     * Set visitors
     *
     * @param collection $visitors
     * @return self
     */
    public function setVisitors($visitors) {
        $this->visitors = $visitors;
        return $this;
    }

    /**
     * Get visitors
     *
     * @return collection $visitors
     */
    public function getVisitors() {
        return $this->visitors;
    }

    /**
     * Set isHiring
     *
     * @param boolean $isHiring
     * @return self
     */
    public function setIsHiring($isHiring) {
        $this->isHiring = $isHiring;
        return $this;
    }

    /**
     * Get isHiring
     *
     * @return boolean $isHiring
     */
    public function getIsHiring() {
        return $this->isHiring;
    }

    /**
     * Set accessRequestedBy
     *
     * @param collection $accessRequestedBy
     * @return self
     */
    public function setAccessRequestedBy($accessRequestedBy) {
        $this->accessRequestedBy = $accessRequestedBy;
        return $this;
    }

    /**
     * Set validDescriptionBox
     *
     * @param boolean $validDescriptionBox
     * @return self
     */
    public function setValidDescriptionBox($validDescriptionBox) {
        $this->validDescriptionBox = $validDescriptionBox;
        return $this;
    }

    /**
     * Get validDescriptionBox
     *
     * @return boolean $validDescriptionBox
     */
    public function getValidDescriptionBox() {
        return $this->validDescriptionBox;
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

    /**
     * Set website
     *
     * @param string $website
     * @return self
     */
    public function setWebsite($website) {
        $this->website = $website;
        return $this;
    }

    /**
     * Get website
     *
     * @return string $website
     */
    public function getWebsite() {
        return $this->website;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     * @return self
     */
    public function setTwitter($twitter) {
        $this->twitter = $twitter;
        return $this;
    }

    /**
     * Get twitter
     *
     * @return string $twitter
     */
    public function getTwitter() {
        return $this->twitter;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     * @return self
     */
    public function setFacebook($facebook) {
        $this->facebook = $facebook;
        return $this;
    }

    /**
     * Get facebook
     *
     * @return string $facebook
     */
    public function getFacebook() {
        return $this->facebook;
    }

    /**
     * Set instagram
     *
     * @param string $instagram
     * @return self
     */
    public function setInstagram($instagram) {
        $this->instagram = $instagram;
        return $this;
    }

    /**
     * Get instagram
     *
     * @return string $instagram
     */
    public function getInstagram() {
        return $this->instagram;
    }

    /**
     * Set linkedIn
     *
     * @param string $linkedIn
     * @return self
     */
    public function setLinkedIn($linkedIn) {
        $this->linkedIn = $linkedIn;
        return $this;
    }

    /**
     * Get linkedIn
     *
     * @return string $linkedIn
     */
    public function getLinkedIn() {
        return $this->linkedIn;
    }

    /**
     * Set blog
     *
     * @param string $blog
     * @return self
     */
    public function setBlog($blog) {
        $this->blog = $blog;
        return $this;
    }

    /**
     * Get blog
     *
     * @return string $blog
     */
    public function getBlog() {
        return $this->blog;
    }

    /**
     * Get companyPercentage
     *
     * @return Int $companyPercentage
     */
    public function getCompanyPercentage() {
        return $this->companyPercentage;
    }

    /**
     * Set companyPercentage
     *
     * @param Int $companyPercentage
     * @return self
     */
    public function setCompanyPercentage($companyPercentage) {
        $this->companyPercentage = $companyPercentage;
        return $this;
    }

    /**
     * Get companyPoints
     *
     * @return Int $companyPoints
     */
    public function getCompanyPoints() {
        return $this->companyPoints;
    }

    /**
     * Set companyPoints
     *
     * @param Int $companyPoints
     * @return self
     */
    public function setCompanyPoints($companyPoints) {
        $this->companyPoints = $companyPoints;
        return $this;
    }

    /**
     * Get companyRank
     *
     * @return Int $companyRank
     */
    public function getCompanyRank() {
        return $this->companyRank;
    }

    /**
     * Set companyRank
     *
     * @param Int $companyRank
     * @return self
     */
    public function setCompanyRank($companyRank) {
        $this->companyRank = $companyRank;
        return $this;
    }

    /**
     * Get oldCompanyRank
     *
     * @return Int $oldCompanyRank
     */
    public function getOldCompanyRank() {
        return $this->oldCompanyRank;
    }

    /**
     * Set oldCompanyRank
     *
     * @param Int $oldCompanyRank
     * @return self
     */
    public function setOldCompanyRank($oldCompanyRank) {
        $this->oldCompanyRank = $oldCompanyRank;
        return $this;
    }

    /**
     * Set foundedin
     *
     * @param string $foundedin
     * @return self
     */
    public function setFoundedin($foundedin) {
        $this->foundedin = $foundedin;
        return $this;
    }

    /**
     * Get foundedin
     *
     * @return string $foundedin
     */
    public function getFoundedin() {
        return $this->foundedin;
    }

    /**
     * Set founders
     *
     * @param string $founders
     * @return self
     */
    public function setFounders($founders) {
        $this->founders = $founders;
        return $this;
    }

    /**
     * Get founders
     *
     * @return string $founders
     */
    public function getFounders() {
        return $this->founders;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return self
     */
    public function setLat($lat) {
        $this->lat = $lat;
        return $this;
    }

    /**
     * Get lat
     *
     * @return float $lat
     */
    public function getLat() {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param float $lng
     * @return self
     */
    public function setLng($lng) {
        $this->lng = $lng;
        return $this;
    }

    /**
     * Get lng
     *
     * @return float $lng
     */
    public function getLng() {
        return $this->lng;
    }

}
