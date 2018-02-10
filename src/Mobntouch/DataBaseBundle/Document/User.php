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
class User extends BaseUser {

    /**
     * @MongoDB\Id(strategy="auto")
     */
    public $id;

    /**
     * @MongoDB\String
     */
    public $username;

    /**
     * @MongoDB\Boolean
     */
    public $validated;

    /**
     * @MongoDB\String
     */
    protected $emailValidationHash;

    /**
     * @MongoDB\String
     */
    public $name;

    /**
     * @MongoDB\String
     */
    public $lastname;

    /**
     * @MongoDB\String
     */
    public $email;

    /**
     * @MongoDB\String
     */
    public $facebookId;

    /**
     * @MongoDB\String
     */
    public $facebookUrl;

    /**
     * @MongoDB\String
     */
    public $facebookAuthToken;

    /**
     * @MongoDB\String
     */
    public $googleId;

    /**
     * @MongoDB\String
     */
    public $googleAuthToken;

    /**
     * @MongoDB\String
     */
    public $googleUrl;

    /**
     * @MongoDB\String
     */
    public $contactEmail;

    /**
     * @MongoDB\String
     */
    public $company;

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
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $companyPage;

    /**
     * @MongoDB\String
     */
    public $jobTitle;

    /**
     * @MongoDB\Int
     */
    public $grossSalary;

    /**
     * @MongoDB\String
     */
    public $currency;

    /**
     * @MongoDB\Boolean
     */
    public $hasEmployer;

    /**
     * @MongoDB\String
     */
    public $currentStatus;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $interestedIn;

    /**
     * @MongoDB\String
     */
    public $gender;

    /**
     * @MongoDB\String
     */
    public $birthday;

    /**
     * @MongoDB\String
     */
    public $birthdayMM;

    /**
     * @MongoDB\String
     */
    public $birthdayDD;

    /**
     * @MongoDB\String
     */
    public $birthdayYYYY;

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
     * @MongoDB\String
     */
    public $lat;

    /**
     * @MongoDB\String
     */
    public $lng;

    /**
     * @MongoDB\String
     */
    public $skype;

    /**
     * @MongoDB\String
     */
    public $linkedin;

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
    public $github;

    /**
     * @MongoDB\String
     */
    public $stackOverflow;

    /**
     * @MongoDB\String
     */
    public $dribbble;

    /**
     * @MongoDB\String
     */
    public $behance;

    /**
     * @MongoDB\String
     */
    public $instagram;

    /**
     * @MongoDB\String
     */
    public $pinterest;

    /**
     * @MongoDB\String
     */
    public $otherLink;

    /**
     * @MongoDB\String
     */
    public $phone;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $imContacts;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $profileOrder;

    /**
     * @MongoDB\String
     */
    public $miniResume;

    /**
     * @MongoDB\String
     */
    public $website;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $services;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $suggestedServices;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $buyTraffic;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $sellTraffic;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $customServices;

    /**
     * @MongoDB\String
     */
    public $customBoxname;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $trackingServices;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $references;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $competences;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $languages;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $experiences;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $educations;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $events;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $following;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $followers;

    /**
     * @MongoDB\Boolean
     */
    public $premium;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $iosApps;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $androidApps;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $categories;

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
    public $paymentTerms;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $paymentMethods;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $settings;

    /**
     * @MongoDB\Float
     */
    public $responseRate;

    /**
     * @MongoDB\Int
     */
    public $emailsNotifications;

    /**
     * @MongoDB\Int
     */
    public $totalReceivedEmails;

    /**
     * @MongoDB\Int
     */
    public $totalSentEmails;

    /**
     * @MongoDB\Int
     */
    public $totalRepliedEmails;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $search;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $keywords;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $whoVisitedMe;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $iVisited;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $inTouch;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $notNow;

    /**
     * @MongoDB\Int
     */
    public $inTouchCounter;

    /**
     * @MongoDB\Int
     */
    public $inBusinessRelationCounter;

    /**
     * @MongoDB\Int
     */
    public $alertsNotifications;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $alerts;

    /**
     * @MongoDB\Boolean
     */
    public $privacyHidden;

    /**
     * @MongoDB\String
     */
    private $token;

    /**
     * @MongoDB\String
     */
    private $updateDate;

    /**
     * @MongoDB\String
     */
    public $version;

    /**
     * @MongoDB\String
     */
    public $summary;

    /**
     * @MongoDB\String
     */
    private $linkedInAccessToken;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    private $linkedInInvites;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $importedConnections;

    /**
     * @MongoDB\String
     */
    private $linkedinID;

    /**
     * @MongoDB\Boolean
     */
    public $skipStatus;

    /**
     * @MongoDB\Boolean
     */
    public $hasSyncLinkedin;

    /**
     * @MongoDB\Boolean
     */
    public $hasLinkdinshare;

    /**
     * @MongoDB\Boolean
     */
    public $emailValidation;

    /**
     * @MongoDB\Boolean
     */
    public $hasVisitedOwnProfile;

    /**
     * @MongoDB\Boolean
     */
    public $hasBrowsedJobs;

    /**
     * @MongoDB\Boolean
     */
    public $hasVisitedQA;

    /**
     * @MongoDB\Boolean
     */
    public $hasEditedOwnProfile;

    /**
     * @MongoDB\String
     */
    public $linkedInAccessTokenDate;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    private $linkedInCompanies;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    private $linkedInCompaniesID;

    /**
     * @MongoDB\String
     */
    public $feedAccessTime;

    /**
     *
     * @Assert\File(
     * maxSize = "6M",
     * 	 notFoundMessage = "Max 6M"
     *  	)
     */
    //protected $file;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $attending;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $favoriteOffers;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $repliedOffers;

    /**
     * @MongoDB\String
     */
    public $lastOffersCounter;

    /**
     * @MongoDB\Int
     */
    public $offersNotifications;

    /**
     * @MongoDB\String
     */
    public $profilePercentage;

    /**
     * @MongoDB\Int
     */
    public $profilePoints;

    /**
     * @MongoDB\Int
     */
    public $oldProfilePoints;

    /**
     * @MongoDB\String
     */
    public $invitedBy;

    /**
     * @MongoDB\String
     */
    protected $playerId;

    /**
     * @MongoDB\Boolean
     */
    public $hasPostedJob;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $jobFilters;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    private $weeklyJobMails;

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
     * Set validated
     *
     * @param boolean $validated
     * @return self
     */
    public function setValidated($validated) {
        $this->validated = $validated;
        return $this;
    }

    /**
     * Get validated
     *
     * @return boolean $validated
     */
    public function getValidated() {
        return $this->validated;
    }

    /**
     * Set emailValidationHash
     *
     * @param string $emailValidationHash
     * @return self
     */
    public function setEmailValidationHash($emailValidationHash) {
        $this->emailValidationHash = $emailValidationHash;
        return $this;
    }

    /**
     * Get emailValidationHash
     *
     * @return string $emailValidationHash
     */
    public function getEmailValidationHash() {
        return $this->emailValidationHash;
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
     * Set lastname
     *
     * @param string $lastname
     * @return self
     */
    public function setLastname($lastname) {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string $lastname
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return self
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set facebookId
     *
     * @param string $facebookId
     * @return self
     */
    public function setFacebookId($facebookId) {
        $this->facebookId = $facebookId;
        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string $facebookId
     */
    public function getFacebookId() {
        return $this->facebookId;
    }

    /**
     * Set facebookAuthToken
     *
     * @param string $facebookAuthToken
     * @return self
     */
    public function setFacebookAuthToken($facebookAuthToken) {
        $this->facebookAuthToken = $facebookAuthToken;
        return $this;
    }

    /**
     * Get facebookAuthToken
     *
     * @return string $facebookAuthToken
     */
    public function getFacebookAuthToken() {
        return $this->facebookAuthToken;
    }

    /**
     * Set facebook_url
     *
     * @param string $facebookUrl
     * @return self
     */
    public function setFacebookUrl($facebookUrl) {
        $this->facebookUrl = $facebookUrl;
        return $this;
    }

    /**
     * Get facebookUrl
     *
     * @return string $facebookUrl
     */
    public function getFacebookUrl() {
        return $this->facebookUrl;
    }

    /**
     * Set googleId
     *
     * @param string $googleId
     * @return self
     */
    public function setGoogleId($googleId) {
        $this->googleId = $googleId;
        return $this;
    }

    /**
     * Get googleId
     *
     * @return string $googleId
     */
    public function getGoogleId() {
        return $this->googleId;
    }

    /**
     * Set googleAuthToken
     *
     * @param string $googleAuthToken
     * @return self
     */
    public function setGoogleAuthToken($googleAuthToken) {
        $this->googleAuthToken = $googleAuthToken;
        return $this;
    }

    /**
     * Get googleAuthToken
     *
     * @return string $googleAuthToken
     */
    public function getGoogleAuthToken() {
        return $this->googleAuthToken;
    }

    /**
     * Set googleUrl
     *
     * @param string $googleUrl
     * @return self
     */
    public function setGoogleUrl($googleUrl) {
        $this->googleUrl = $googleUrl;
        return $this;
    }

    /**
     * Get googleUrl
     *
     * @return string $googleUrl
     */
    public function getGoogleUrl() {
        return $this->googleUrl;
    }

    /**
     * Set contactEmail
     *
     * @param string $contactEmail
     * @return self
     */
    public function setContactEmail($contactEmail) {
        $this->contactEmail = $contactEmail;
        return $this;
    }

    /**
     * Get contactEmail
     *
     * @return string $contactEmail
     */
    public function getContactEmail() {
        return $this->contactEmail;
    }

    /**
     * Set company
     *
     * @param string $company
     * @return self
     */
    public function setCompany($company) {
        $this->company = $company;
        return $this;
    }

    /**
     * Get company
     *
     * @return string $company
     */
    public function getCompany() {
        return $this->company;
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
     * Set companyPage
     *
     * @param collection $companyPage
     * @return self
     */
    public function setCompanyPage($companyPage) {
        $this->companyPage = $companyPage;
        return $this;
    }

    /**
     * Get companyPage
     *
     * @return collection $companyPage
     */
    public function getCompanyPage() {
        return $this->companyPage;
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
     * Get jobTitle
     *
     * @return string $jobTitle
     */
    public function getJobTitle() {
        return $this->jobTitle;
    }

    /**
     * Set grossSalary
     *
     * @param int $grossSalary
     * @return self
     */
    public function setGrossSalary($grossSalary) {
        $this->grossSalary = $grossSalary;
        return $this;
    }

    /**
     * Get grossSalary
     *
     * @return int $grossSalary
     */
    public function getGrossSalary() {
        return $this->grossSalary;
    }

    /**
     * Set currency
     *
     * @param String $currency
     * @return self
     */
    public function setCurrency($currency) {
        $this->currency = $currency;
        return $this;
    }

    /**
     * Get currency
     *
     * @return string $currency
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * Set hasEmployer
     *
     * @param Boolean $hasEmployer
     * @return self
     */
    public function setHasEmployer($hasEmployer) {
        $this->hasEmployer = $hasEmployer;
        return $this;
    }

    /**
     * Get hasEmployer
     *
     * @return Boolean $hasEmployer
     */
    public function getHasEmployer() {
        return $this->hasEmployer;
    }

    /**
     * Set currentStatus
     *
     * @param String $currentStatus
     * @return self
     */
    public function setCurrentStatus($currentStatus) {
        $this->currentStatus = $currentStatus;
        return $this;
    }

    /**
     * Get interestedIn
     *
     * @return collection $interestedIn
     */
    public function getInterestedIn() {
        return $this->interestedIn;
    }

    /**
     * Set interestedIn
     *
     * @param collection $interestedIn
     * @return self
     */
    public function setInterestedIn($interestedIn) {
        $this->interestedIn = $interestedIn;
        return $this;
    }

    /**
     * Get currentStatus
     *
     * @return String $currentStatus
     */
    public function getCurrentStatus() {
        return $this->currentStatus;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return self
     */
    public function setGender($gender) {
        $this->gender = $gender;
        return $this;
    }

    /**
     * Get gender
     *
     * @return string $gender
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * Set birthday
     *
     * @param string $birthday
     * @return self
     */
    public function setBirthday($birthday) {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * Get birthday
     *
     * @return string $birthday
     */
    public function getBirthday() {
        return $this->birthday;
    }

    /**
     * Set birthdayMM
     *
     * @param string $birthdayMM
     * @return self
     */
    public function setBirthdayMM($birthdayMM) {
        $this->birthdayMM = $birthdayMM;
        return $this;
    }

    /**
     * Get birthdayMM
     *
     * @return string $birthdayMM
     */
    public function getBirthdayMM() {
        return $this->birthdayMM;
    }

    /**
     * Set birthdayDD
     *
     * @param string $birthdayDD
     * @return self
     */
    public function setBirthdayDD($birthdayDD) {
        $this->birthdayDD = $birthdayDD;
        return $this;
    }

    /**
     * Get birthdayDD
     *
     * @return string $birthdayDD
     */
    public function getBirthdayDD() {
        return $this->birthdayDD;
    }

    /**
     * Set birthdayYYYY
     *
     * @param string $birthdayYYYY
     * @return self
     */
    public function setBirthdayYYYY($birthdayYYYY) {
        $this->birthdayYYYY = $birthdayYYYY;
        return $this;
    }

    /**
     * Get birthdayYYYY
     *
     * @return string $birthdayYYYY
     */
    public function getBirthdayYYYY() {
        return $this->birthdayYYYY;
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
     * Set lat
     *
     * @param string $lat
     * @return self
     */
    public function setLat($lat) {
        $this->lat = $lat;
        return $this;
    }

    /**
     * Get lat
     *
     * @return string $lat
     */
    public function getLat() {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param string $lng
     * @return self
     */
    public function setLng($lng) {
        $this->lng = $lng;
        return $this;
    }

    /**
     * Get lng
     *
     * @return string $lng
     */
    public function getLng() {
        return $this->lng;
    }

    /**
     * Set skype
     *
     * @param string $skype
     * @return self
     */
    public function setSkype($skype) {
        $this->skype = $skype;
        return $this;
    }

    /**
     * Get skype
     *
     * @return string $skype
     */
    public function getSkype() {
        return $this->skype;
    }

    /**
     * Set linkedin
     *
     * @param string $linkedin
     * @return self
     */
    public function setLinkedin($linkedin) {
        $this->linkedin = $linkedin;
        return $this;
    }

    /**
     * Get linkedin
     *
     * @return string $linkedin
     */
    public function getLinkedin() {
        return $this->linkedin;
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
     * Set github
     *
     * @param string $github
     * @return self
     */
    public function setGithub($github) {
        $this->github = $github;
        return $this;
    }

    /**
     * Get github
     *
     * @return string $github
     */
    public function getGithub() {
        return $this->github;
    }

    /**
     * Set stackOverflow
     *
     * @param string $stackOverflow
     * @return self
     */
    public function setStackOverflow($stackOverflow) {
        $this->stackOverflow = $stackOverflow;
        return $this;
    }

    /**
     * Get stackOverflow
     *
     * @return string $stackOverflow
     */
    public function getStackOverflow() {
        return $this->stackOverflow;
    }

    /**
     * Set dribbble
     *
     * @param string $dribbble
     * @return self
     */
    public function setDribbble($dribbble) {
        $this->dribbble = $dribbble;
        return $this;
    }

    /**
     * Get dribbble
     *
     * @return string $dribbble
     */
    public function getDribbble() {
        return $this->dribbble;
    }

    /**
     * Set behance
     *
     * @param string $behance
     * @return self
     */
    public function setBehance($behance) {
        $this->behance = $behance;
        return $this;
    }

    /**
     * Get behance
     *
     * @return string $behance
     */
    public function getBehance() {
        return $this->behance;
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
     * Set pinterest
     *
     * @param string $pinterest
     * @return self
     */
    public function setPinterest($pinterest) {
        $this->pinterest = $pinterest;
        return $this;
    }

    /**
     * Get pinterest
     *
     * @return string $pinterest
     */
    public function getPinterest() {
        return $this->pinterest;
    }

    /**
     * Set otherLink
     *
     * @param string $otherLink
     * @return self
     */
    public function setOtherLink($otherLink) {
        $this->otherLink = $otherLink;
        return $this;
    }

    /**
     * Get otherLink
     *
     * @return string $otherLink
     */
    public function getOtherLink() {
        return $this->otherLink;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return self
     */
    public function setPhone($phone) {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Get phone
     *
     * @return string $phone
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Set imContacts
     *
     * @param collection $imContacts
     * @return self
     */
    public function setImContacts($imContacts) {
        $this->imContacts = $imContacts;
        return $this;
    }

    /**
     * Get imContacts
     *
     * @return string $imContacts
     */
    public function getImContacts() {
        return $this->imContacts;
    }

    /**
     * Set profileOrder
     *
     * @param collection $profileOrder
     * @return self
     */
    public function setProfileOrder($profileOrder) {
        $this->profileOrder = $profileOrder;
        return $this;
    }

    /**
     * Get profileOrder
     *
     * @return collection $profileOrder
     */
    public function getProfileOrder() {
        return $this->profileOrder;
    }

    /**
     * Set miniResume
     *
     * @param string $miniResume
     * @return self
     */
    public function setMiniResume($miniResume) {
        $this->miniResume = $miniResume;
        return $this;
    }

    /**
     * Get miniResume
     *
     * @return string $miniResume
     */
    public function getMiniResume() {
        return $this->miniResume;
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
     * Set services
     *
     * @param collection $services
     * @return self
     */
    public function setServices($services) {
        $this->services = $services;
        return $this;
    }

    /**
     * Get services
     *
     * @return collection $services
     */
    public function getServices() {
        return $this->services;
    }

    /**
     * Set suggestedServices
     *
     * @param collection $suggestedServices
     * @return self
     */
    public function setSuggestedServices($suggestedServices) {
        $this->suggestedServices = $suggestedServices;
        return $this;
    }

    /**
     * Get suggestedServices
     *
     * @return collection $suggestedServices
     */
    public function getSuggestedServices() {
        return $this->suggestedServices;
    }

    /**
     * Set buyTraffic
     *
     * @param collection $buyTraffic
     * @return self
     */
    public function setBuyTraffic($buyTraffic) {
        $this->buyTraffic = $buyTraffic;
        return $this;
    }

    /**
     * Get buyTraffic
     *
     * @return collection $buyTraffic
     */
    public function getBuyTraffic() {
        return $this->buyTraffic;
    }

    /**
     * Set sellTraffic
     *
     * @param collection $sellTraffic
     * @return self
     */
    public function setSellTraffic($sellTraffic) {
        $this->sellTraffic = $sellTraffic;
        return $this;
    }

    /**
     * Get sellTraffic
     *
     * @return collection $sellTraffic
     */
    public function getSellTraffic() {
        return $this->sellTraffic;
    }

    /**
     * Set customServices
     *
     * @param collection $customServices
     * @return self
     */
    public function setCustomServices($customServices) {
        $this->customServices = $customServices;
        return $this;
    }

    /**
     * Get customBoxname
     *
     * @return string $customBoxname
     */
    public function getCustomBoxname() {
        return $this->customBoxname;
    }
    
    /**
     * Set customBoxname
     *
     * @param string $customBoxname
     * @return self
     */
    public function setCustomBoxname($customBoxname) {
        $this->customBoxname = $customBoxname;
        return $this;
    }

    /**
     * Get customServices
     *
     * @return collection $customServices
     */
    public function getCustomServices() {
        return $this->customServices;
    }    

    /**
     * Set trackingServices
     *
     * @param collection $trackingServices
     * @return self
     */
    public function setTrackingServices($trackingServices) {
        $this->trackingServices = $trackingServices;
        return $this;
    }

    /**
     * Get trackingServices
     *
     * @return collection $trackingServices
     */
    public function getTrackingServices() {
        return $this->trackingServices;
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
     * Set competences
     *
     * @param collection $competences
     * @return self
     */
    public function setCompetences($competences) {
        $this->competences = $competences;
        return $this;
    }

    /**
     * Get competences
     *
     * @return collection $competences
     */
    public function getCompetences() {
        return $this->competences;
    }

    /**
     * Set languages
     *
     * @param collection $languages
     * @return self
     */
    public function setLanguages($languages) {
        $this->languages = $languages;
        return $this;
    }

    /**
     * Get languages
     *
     * @return collection $languages
     */
    public function getLanguages() {
        return $this->languages;
    }

    /**
     * Set experiences
     *
     * @param collection $experiences
     * @return self
     */
    public function setExperiences($experiences) {
        $this->experiences = $experiences;
        return $this;
    }

    /**
     * Get experiences
     *
     * @return collection $experiences
     */
    public function getExperiences() {
        return $this->experiences;
    }

    /**
     * Set educations
     *
     * @param collection $educations
     * @return self
     */
    public function setEducations($educations) {
        $this->educations = $educations;
        return $this;
    }

    /**
     * Get educations
     *
     * @return collection $educations
     */
    public function getEducations() {
        return $this->educations;
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
     * Set following
     *
     * @param collection $following
     * @return self
     */
    public function setFollowing($following) {
        $this->following = $following;
        return $this;
    }

    /**
     * Get following
     *
     * @return collection $following
     */
    public function getFollowing() {
        return $this->following;
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
     * Set premium
     *
     * @param boolean $premium
     * @return self
     */
    public function setPremium($premium) {
        $this->premium = $premium;
        return $this;
    }

    /**
     * Get premium
     *
     * @return boolean $premium
     */
    public function getPremium() {
        return $this->premium;
    }

    /**
     * Set iosApps
     *
     * @param collection $iosApps
     * @return self
     */
    public function setIosApps($iosApps) {
        $this->iosApps = $iosApps;
        return $this;
    }

    /**
     * Get iosApps
     *
     * @return collection $iosApps
     */
    public function getIosApps() {
        return $this->iosApps;
    }

    /**
     * Set androidApps
     *
     * @param collection $androidApps
     * @return self
     */
    public function setAndroidApps($androidApps) {
        $this->androidApps = $androidApps;
        return $this;
    }

    /**
     * Get androidApps
     *
     * @return collection $androidApps
     */
    public function getAndroidApps() {
        return $this->androidApps;
    }

    /**
     * Set categories
     *
     * @param collection $categories
     * @return self
     */
    public function setCategories($categories) {
        $this->categories = $categories;
        return $this;
    }

    /**
     * Get categories
     *
     * @return collection $categories
     */
    public function getCategories() {
        return $this->categories;
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
     * Set paymentTerms
     *
     * @param collection $paymentTerms
     * @return self
     */
    public function setPaymentTerms($paymentTerms) {
        $this->paymentTerms = $paymentTerms;
        return $this;
    }

    /**
     * Get paymentTerms
     *
     * @return collection $paymentTerms
     */
    public function getPaymentTerms() {
        return $this->paymentTerms;
    }

    /**
     * Set paymentMethods
     *
     * @param collection $paymentMethods
     * @return self
     */
    public function setPaymentMethods($paymentMethods) {
        $this->paymentMethods = $paymentMethods;
        return $this;
    }

    /**
     * Get paymentMethods
     *
     * @return collection $paymentMethods
     */
    public function getPaymentMethods() {
        return $this->paymentMethods;
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
     * Set responseRate
     *
     * @param float $responseRate
     * @return self
     */
    public function setResponseRate($responseRate) {
        $this->responseRate = $responseRate;
        return $this;
    }

    /**
     * Get responseRate
     *
     * @return float $responseRate
     */
    public function getResponseRate() {
        return $this->responseRate;
    }

    /**
     * Set emailsNotifications
     *
     * @param int $emailsNotifications
     * @return self
     */
    public function setEmailsNotifications($emailsNotifications) {
        $this->emailsNotifications = $emailsNotifications;
        return $this;
    }

    /**
     * Get emailsNotifications
     *
     * @return int $emailsNotifications
     */
    public function getEmailsNotifications() {
        return $this->emailsNotifications;
    }

    /**
     * Set totalReceivedEmails
     *
     * @param int $totalReceivedEmails
     * @return self
     */
    public function setTotalReceivedEmails($totalReceivedEmails) {
        $this->totalReceivedEmails = $totalReceivedEmails;
        return $this;
    }

    /**
     * Get totalReceivedEmails
     *
     * @return int $totalReceivedEmails
     */
    public function getTotalReceivedEmails() {
        return $this->totalReceivedEmails;
    }

    /**
     * Set totalSentEmails
     *
     * @param int $totalSentEmails
     * @return self
     */
    public function setTotalSentEmails($totalSentEmails) {
        $this->totalSentEmails = $totalSentEmails;
        return $this;
    }

    /**
     * Get totalSentEmails
     *
     * @return int $totalSentEmails
     */
    public function getTotalSentEmails() {
        return $this->totalSentEmails;
    }

    /**
     * Set totalRepliedEmails
     *
     * @param int $totalRepliedEmails
     * @return self
     */
    public function setTotalRepliedEmails($totalRepliedEmails) {
        $this->totalRepliedEmails = $totalRepliedEmails;
        return $this;
    }

    /**
     * Get totalRepliedEmails
     *
     * @return int $totalRepliedEmails
     */
    public function getTotalRepliedEmails() {
        return $this->totalRepliedEmails;
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
     * Set keywords
     *
     * @param collection $keywords
     * @return self
     */
    public function setKeywords($keywords) {
        $this->keywords = $keywords;
        return $this;
    }

    /**
     * Get keywords
     *
     * @return collection $keywords
     */
    public function getKeywords() {
        return $this->keywords;
    }

    /**
     * Set whoVisitedMe
     *
     * @param collection $whoVisitedMe
     * @return self
     */
    public function setWhoVisitedMe($whoVisitedMe) {
        $this->whoVisitedMe = $whoVisitedMe;
        return $this;
    }

    /**
     * Get whoVisitedMe
     *
     * @return collection $whoVisitedMe
     */
    public function getWhoVisitedMe() {
        return $this->whoVisitedMe;
    }

    /**
     * Set iVisited
     *
     * @param collection $iVisited
     * @return self
     */
    public function setIVisited($iVisited) {
        $this->iVisited = $iVisited;
        return $this;
    }

    /**
     * Get iVisited
     *
     * @return collection $iVisited
     */
    public function getIVisited() {
        return $this->iVisited;
    }

    /**
     * Set inTouch
     *
     * @param collection $inTouch
     * @return self
     */
    public function setInTouch($inTouch) {
        $this->inTouch = $inTouch;
        return $this;
    }

    /**
     * Get inTouch
     *
     * @return collection $inTouch
     */
    public function getInTouch() {
        return $this->inTouch;
    }

    /**
     * Set notNow
     *
     * @param collection $notNow
     * @return self
     */
    public function setNotNow($notNow) {
        $this->notNow = $notNow;
        return $this;
    }

    /**
     * Get notNow
     *
     * @return collection $notNow
     */
    public function getNotNow() {
        return $this->notNow;
    }

    /**
     * Set inTouchCounter
     *
     * @param int $inTouchCounter
     * @return self
     */
    public function setInTouchCounter($inTouchCounter) {
        $this->inTouchCounter = $inTouchCounter;
        return $this;
    }

    /**
     * Get inTouchCounter
     *
     * @return int $inTouchCounter
     */
    public function getInTouchCounter() {
        return $this->inTouchCounter;
    }

    /**
     * Set inBusinessRelationCounter
     *
     * @param int $inBusinessRelationCounter
     * @return self
     */
    public function setInBusinessRelationCounter($inBusinessRelationCounter) {
        $this->inBusinessRelationCounter = $inBusinessRelationCounter;
        return $this;
    }

    /**
     * Get inBusinessRelationCounter
     *
     * @return int $inBusinessRelationCounter
     */
    public function getInBusinessRelationCounter() {
        return $this->inBusinessRelationCounter;
    }

    /**
     * Set alertsNotifications
     *
     * @param int $alertsNotifications
     * @return self
     */
    public function setAlertsNotifications($alertsNotifications) {
        $this->alertsNotifications = $alertsNotifications;
        return $this;
    }

    /**
     * Get alertsNotifications
     *
     * @return int $alertsNotifications
     */
    public function getAlertsNotifications() {
        return $this->alertsNotifications;
    }

    /**
     * Set alerts
     *
     * @param collection $alerts
     * @return self
     */
    public function setAlerts($alerts) {
        $this->alerts = $alerts;
        return $this;
    }

    /**
     * Get alerts
     *
     * @return collection $alerts
     */
    public function getAlerts() {
        return $this->alerts;
    }

    /**
     * Set privacyHidden
     *
     * @param boolean $privacyHidden
     * @return self
     */
    public function setPrivacyHidden($privacyHidden) {
        $this->privacyHidden = $privacyHidden;
        return $this;
    }

    /**
     * Get privacyHidden
     *
     * @return boolean $privacyHidden
     */
    public function getPrivacyHidden() {
        return $this->privacyHidden;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return self
     */
    public function setToken($token) {
        $this->token = $token;
        return $this;
    }

    /**
     * Get token
     *
     * @return string $token
     */
    public function getToken() {
        return $this->token;
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
     * Set version
     *
     * @param string $version
     * @return self
     */
    public function setVersion($version) {
        $this->version = $version;
        return $this;
    }

    /**
     * Get version
     *
     * @return string $version
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return self
     */
    public function setSummary($summary) {
        $this->summary = $summary;
        return $this;
    }

    /**
     * Get summary
     *
     * @return string $summary
     */
    public function getSummary() {
        return $this->summary;
    }

    /**
     * Set linkedInAccessToken
     *
     * @param string $linkedInAccessToken
     * @return self
     */
    public function setLinkedInAccessToken($linkedInAccessToken) {
        $this->linkedInAccessToken = $linkedInAccessToken;
        return $this;
    }

    /**
     * Get linkedInAccessToken
     *
     * @return string $linkedInAccessToken
     */
    public function getLinkedInAccessToken() {
        return $this->linkedInAccessToken;
    }

    /**
     * Set linkedInInvites
     *
     * @param collection $linkedInInvites
     * @return self
     */
    public function setLinkedInInvites($linkedInInvites) {
        $this->linkedInInvites = $linkedInInvites;
        return $this;
    }

    /**
     * Get linkedInInvites
     *
     * @return collection $linkedInInvites
     */
    public function getLinkedInInvites() {
        return $this->linkedInInvites;
    }

    /**
     * Set importedConnections
     *
     * @param collection $importedConnections
     * @return self
     */
    public function setImportedConnections($importedConnections) {
        $this->importedConnections = $importedConnections;
        return $this;
    }

    /**
     * Get importedConnections
     *
     * @return collection $importedConnections
     */
    public function getImportedConnections() {
        return $this->importedConnections;
    }

    /**
     * Set linkedinID
     *
     * @param string $linkedinID
     * @return self
     */
    public function setLinkedinID($linkedinID) {
        $this->linkedinID = $linkedinID;
        return $this;
    }

    /**
     * Get linkedinID
     *
     * @return string $linkedinID
     */
    public function getLinkedinID() {
        return $this->linkedinID;
    }

    /**
     * Get skipStatus
     *
     * @return boolean $skipStatus
     */
    public function getSkipStatus() {
        return $this->skipStatus;
    }

    /**
     * Set skipStatus
     *
     * @param boolean $skipStatus
     * @return self
     */
    public function setSkipStatus($skipStatus) {
        $this->skipStatus = $skipStatus;
        return $this;
    }

    /**
     * Get profilePercentage
     *
     * @return boolean $profilePercentage
     */
    public function getProfilePercentage() {
        return $this->profilePercentage;
    }

    /**
     * Set profilePercentage
     *
     * @param boolean $profilePercentage
     * @return self
     */
    public function setProfilePercentages($profilePercentage) {
        $this->profilePercentage = $profilePercentage;
        return $this;
    }

    /**
     * Get profilePoints
     *
     * @return Int $profilePoints
     */
    public function getProfilePoints() {
        return $this->profilePoints;
    }

    /**
     * Set profilePoints
     *
     * @param Int $profilePoints
     * @return self
     */
    public function setProfilePoints($profilePoints) {
        $this->profilePoints = $profilePoints;
        return $this;
    }

    /**
     * Get oldProfilePoints
     *
     * @return Int $oldProfilePoints
     */
    public function getOldProfilePoints() {
        return $this->oldProfilePoints;
    }

    /**
     * Set oldProfilePoints
     *
     * @param Int $oldProfilePoints
     * @return self
     */
    public function setOldProfilePoints($oldProfilePoints) {
        $this->oldProfilePoints = $oldProfilePoints;
        return $this;
    }

    /**
     * Get invitedBy
     *
     * @return String $invitedBy
     */
    public function getInvitedBy() {
        return $this->invitedBy;
    }

    /**
     * Set invitedBy
     *
     * @param String $invitedBy
     * @return self
     */
    public function setInvitedBy($invitedBy) {
        $this->invitedBy = $invitedBy;
        return $this;
    }

    /**
     * Set hasSyncLinkedin
     *
     * @param boolean $hasSyncLinkedin
     * @return self
     */
    public function setHasSyncLinkedin($hasSyncLinkedin) {
        $this->hasSyncLinkedin = $hasSyncLinkedin;
        return $this;
    }

    /**
     * Get hasSyncLinkedin
     *
     * @return boolean $hasSyncLinkedin
     */
    public function getHasSyncLinkedin() {
        return $this->hasSyncLinkedin;
    }

    /**
     * Set emailValidation
     *
     * @param boolean $emailValidation
     * @return self
     */
    public function setEmailValidation($emailValidation) {
        $this->emailValidation = $emailValidation;
        return $this;
    }

    /**
     * Get emailValidation
     *
     * @return boolean $emailValidation
     */
    public function getEmailValidation() {
        return $this->emailValidation;
    }

    /**
     * Set hasLinkdinshare
     *
     * @param boolean $hasLinkdinshare
     * @return self
     */
    public function setHasLinkdinshare($hasLinkdinshare) {
        $this->hasLinkdinshare = $hasLinkdinshare;
        return $this;
    }

    /**
     * Get hasLinkdinshare
     *
     * @return boolean $hasLinkdinshare
     */
    public function getHasLinkdinshare() {
        return $this->hasLinkdinshare;
    }

    /**
     * Set hasVisitedOwnProfile
     *
     * @param boolean $hasVisitedOwnProfile
     * @return self
     */
    public function setHasVisitedOwnProfile($hasVisitedOwnProfile) {
        $this->hasVisitedOwnProfile = $hasVisitedOwnProfile;
        return $this;
    }

    /**
     * Get hasVisitedOwnProfile
     *
     * @return boolean $hasVisitedOwnProfile
     */
    public function getHasVisitedOwnProfile() {
        return $this->hasVisitedOwnProfile;
    }

    /**
     * Set hasBrowsedJobs
     *
     * @param boolean $hasBrowsedJobs
     * @return self
     */
    public function setHasBrowsedJobs($hasBrowsedJobs) {
        $this->hasBrowsedJobs = $hasBrowsedJobs;
        return $this;
    }

    /**
     * Get hasBrowsedJobs
     *
     * @return boolean $hasBrowsedJobs
     */
    public function getHasBrowsedJobs() {
        return $this->hasBrowsedJobs;
    }

    /**
     * Set hasVisitedQA
     *
     * @param boolean $hasVisitedQA
     * @return self
     */
    public function setHasVisitedQA($hasVisitedQA) {
        $this->hasVisitedQA = $hasVisitedQA;
        return $this;
    }

    /**
     * Get hasVisitedQA
     *
     * @return boolean $hasVisitedQA
     */
    public function getHasVisitedQA() {
        return $this->hasVisitedQA;
    }

    /**
     * Set hasEditedOwnProfile
     *
     * @param boolean $hasEditedOwnProfile
     * @return self
     */
    public function setHasEditedOwnProfile($hasEditedOwnProfile) {
        $this->hasEditedOwnProfile = $hasEditedOwnProfile;
        return $this;
    }

    /**
     * Get hasEditedOwnProfile
     *
     * @return boolean $hasEditedOwnProfile
     */
    public function getHasEditedOwnProfile() {
        return $this->hasEditedOwnProfile;
    }

    /**
     * Set linkedInAccessTokenDate
     *
     * @param string $linkedInAccessTokenDate
     * @return self
     */
    public function setLinkedInAccessTokenDate($linkedInAccessTokenDate) {
        $this->linkedInAccessTokenDate = $linkedInAccessTokenDate;
        return $this;
    }

    /**
     * Get linkedInAccessTokenDate
     *
     * @return string $linkedInAccessTokenDate
     */
    public function getLinkedInAccessTokenDate() {
        return $this->linkedInAccessTokenDate;
    }

    /**
     * Set linkedInCompanies
     *
     * @param collection $linkedInCompanies
     * @return self
     */
    public function setLinkedInCompanies($linkedInCompanies) {
        $this->linkedInCompanies = $linkedInCompanies;
        return $this;
    }

    /**
     * Get linkedInCompanies
     *
     * @return collection $linkedInCompanies
     */
    public function getLinkedInCompanies() {
        return $this->linkedInCompanies;
    }

    /**
     * Set linkedInCompaniesID
     *
     * @param collection $linkedInCompaniesID
     * @return self
     */
    public function setLinkedInCompaniesID($linkedInCompaniesID) {
        $this->linkedInCompaniesID = $linkedInCompaniesID;
        return $this;
    }

    /**
     * Get linkedInCompaniesID
     *
     * @return collection $linkedInCompaniesID
     */
    public function getLinkedInCompaniesID() {
        return $this->linkedInCompaniesID;
    }

    /**
     * Set feedAccessTime
     *
     * @param string $feedAccessTime
     * @return self
     */
    public function setFeedAccessTime($feedAccessTime) {
        $this->feedAccessTime = $feedAccessTime;
        return $this;
    }

    /**
     * Get feedAccessTime
     *
     * @return string $feedAccessTime
     */
    public function getFeedAccessTime() {
        return $this->feedAccessTime;
    }

    /**
     * Set attending
     *
     * @param collection $attending
     * @return self
     */
    public function setAttending($attending) {
        $this->attending = $attending;
        return $this;
    }

    /**
     * Get attending
     *
     * @return collection $attending
     */
    public function getAttending() {
        return $this->attending;
    }

    /**
     * Set favoriteOffers
     *
     * @param collection $favoriteOffers
     * @return self
     */
    public function setFavoriteOffers($favoriteOffers) {
        $this->favoriteOffers = $favoriteOffers;
        return $this;
    }

    /**
     * Get favoriteOffers
     *
     * @return collection $favoriteOffers
     */
    public function getFavoriteOffers() {
        return $this->favoriteOffers;
    }

    /**
     * Set repliedOffers
     *
     * @param collection $repliedOffers
     * @return self
     */
    public function setRepliedOffers($repliedOffers) {
        $this->repliedOffers = $repliedOffers;
        return $this;
    }

    /**
     * Get repliedOffers
     *
     * @return collection $repliedOffers
     */
    public function getRepliedOffers() {
        return $this->repliedOffers;
    }

    /**
     * Set lastOffersCounter
     *
     * @param string $lastOffersCounter
     * @return self
     */
    public function setLastOffersCounter($lastOffersCounter) {
        $this->lastOffersCounter = $lastOffersCounter;
        return $this;
    }

    /**
     * Get lastOffersCounter
     *
     * @return string $lastOffersCounter
     */
    public function getLastOffersCounter() {
        return $this->lastOffersCounter;
    }

    /**
     * Set offersNotifications
     *
     * @param int $offersNotifications
     * @return self
     */
    public function setOffersNotifications($offersNotifications) {
        $this->offersNotifications = $offersNotifications;
        return $this;
    }

    /**
     * Get offersNotifications
     *
     * @return int $offersNotifications
     */
    public function getOffersNotifications() {
        return $this->offersNotifications;
    }

    /**
     * Set playerId
     *
     * @param string $playerId
     * @return self
     */
    public function setPlayerId($playerId) {
        $this->playerId = $playerId;
        return $this;
    }

    /**
     * Get playerId
     *
     * @return string $playerId
     */
    public function getPlayerId() {
        return $this->playerId;
    }

    /**
     * Set hasPostedJob
     *
     * @param boolean $hasPostedJob
     * @return self
     */
    public function setHasPostedJob($hasPostedJob) {
        $this->hasPostedJob = $hasPostedJob;
        return $this;
    }

    /**
     * Get hasPostedJob
     *
     * @return boolean $hasPostedJob
     */
    public function getHasPostedJob() {
        return $this->hasPostedJob;
    }

    /**
     * Set jobFilters
     *
     * @param collection $jobFilters
     * @return self
     */
    public function setJobFilters($jobFilters) {
        $this->jobFilters = $jobFilters;
        return $this;
    }

    /**
     * Get jobFilters
     *
     * @return collection $jobFilters
     */
    public function getJobFilters() {
        return $this->jobFilters;
    }

    /**
     * Set weeklyJobMails
     *
     * @param collection $weeklyJobMails
     * @return self
     */
    public function setWeeklyJobMails($weeklyJobMails) {
        $this->weeklyJobMails = $weeklyJobMails;
        return $this;
    }

    /**
     * Get weeklyJobMails
     *
     * @return collection $weeklyJobMails
     */
    public function getWeeklyJobMails() {
        return $this->weeklyJobMails;
    }

}
