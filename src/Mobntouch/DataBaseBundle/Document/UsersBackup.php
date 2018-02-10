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
class UsersBackup extends BaseUser {

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
    public $basedCountry;

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
    public $phone;

    /**
     * @MongoDB\String
     */
    public $website;

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
     * @MongoDB\Int
     */
    public $inTouchCounter;

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
     * @MongoDB\String
     */
    private $linkedinID;

    /**
     * @MongoDB\Boolean
     */
    public $hasSyncLinkedin;

    /**
     * @MongoDB\Boolean
     */
    public $hasVisitedOwnProfile;

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
     *
     * @Assert\File(
     * maxSize = "6M",
     *	 notFoundMessage = "Max 6M"
     *  	)
     */
    //protected $file;



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
     * Set validated
     *
     * @param boolean $validated
     * @return self
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;
        return $this;
    }

    /**
     * Get validated
     *
     * @return boolean $validated
     */
    public function getValidated()
    {
        return $this->validated;
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
     * Set lastname
     *
     * @param string $lastname
     * @return self
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string $lastname
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set company
     *
     * @param string $company
     * @return self
     */
    public function setCompany($company)
    {
        $this->company = $company;
        return $this;
    }

    /**
     * Get company
     *
     * @return string $company
     */
    public function getCompany()
    {
        return $this->company;
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
     * @param string $companySubType
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
     * @return string $companySubType
     */
    public function getCompanySubType()
    {
        return $this->companySubType;
    }

    /**
     * Set jobTitle
     *
     * @param string $jobTitle
     * @return self
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;
        return $this;
    }

    /**
     * Get jobTitle
     *
     * @return string $jobTitle
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return self
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * Get gender
     *
     * @return string $gender
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set birthday
     *
     * @param string $birthday
     * @return self
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * Get birthday
     *
     * @return string $birthday
     */
    public function getBirthday()
    {
        return $this->birthday;
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
     * Set basedCountry
     *
     * @param string $basedCountry
     * @return self
     */
    public function setBasedCountry($basedCountry)
    {
        $this->basedCountry = $basedCountry;
        return $this;
    }

    /**
     * Get basedCountry
     *
     * @return string $basedCountry
     */
    public function getBasedCountry()
    {
        return $this->basedCountry;
    }

    /**
     * Set skype
     *
     * @param string $skype
     * @return self
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
        return $this;
    }

    /**
     * Get skype
     *
     * @return string $skype
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * Set linkedin
     *
     * @param string $linkedin
     * @return self
     */
    public function setLinkedin($linkedin)
    {
        $this->linkedin = $linkedin;
        return $this;
    }

    /**
     * Get linkedin
     *
     * @return string $linkedin
     */
    public function getLinkedin()
    {
        return $this->linkedin;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     * @return self
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
        return $this;
    }

    /**
     * Get twitter
     *
     * @return string $twitter
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return self
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Get phone
     *
     * @return string $phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return self
     */
    public function setWebsite($website)
    {
        $this->website = $website;
        return $this;
    }

    /**
     * Get website
     *
     * @return string $website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set buyTraffic
     *
     * @param collection $buyTraffic
     * @return self
     */
    public function setBuyTraffic($buyTraffic)
    {
        $this->buyTraffic = $buyTraffic;
        return $this;
    }

    /**
     * Get buyTraffic
     *
     * @return collection $buyTraffic
     */
    public function getBuyTraffic()
    {
        return $this->buyTraffic;
    }

    /**
     * Set sellTraffic
     *
     * @param collection $sellTraffic
     * @return self
     */
    public function setSellTraffic($sellTraffic)
    {
        $this->sellTraffic = $sellTraffic;
        return $this;
    }

    /**
     * Get sellTraffic
     *
     * @return collection $sellTraffic
     */
    public function getSellTraffic()
    {
        return $this->sellTraffic;
    }

    /**
     * Set references
     *
     * @param collection $references
     * @return self
     */
    public function setReferences($references)
    {
        $this->references = $references;
        return $this;
    }

    /**
     * Get references
     *
     * @return collection $references
     */
    public function getReferences()
    {
        return $this->references;
    }

    /**
     * Set competences
     *
     * @param collection $competences
     * @return self
     */
    public function setCompetences($competences)
    {
        $this->competences = $competences;
        return $this;
    }

    /**
     * Get competences
     *
     * @return collection $competences
     */
    public function getCompetences()
    {
        return $this->competences;
    }

    /**
     * Set experiences
     *
     * @param collection $experiences
     * @return self
     */
    public function setExperiences($experiences)
    {
        $this->experiences = $experiences;
        return $this;
    }

    /**
     * Get experiences
     *
     * @return collection $experiences
     */
    public function getExperiences()
    {
        return $this->experiences;
    }

    /**
     * Set events
     *
     * @param collection $events
     * @return self
     */
    public function setEvents($events)
    {
        $this->events = $events;
        return $this;
    }

    /**
     * Get events
     *
     * @return collection $events
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Set following
     *
     * @param collection $following
     * @return self
     */
    public function setFollowing($following)
    {
        $this->following = $following;
        return $this;
    }

    /**
     * Get following
     *
     * @return collection $following
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * Set premium
     *
     * @param boolean $premium
     * @return self
     */
    public function setPremium($premium)
    {
        $this->premium = $premium;
        return $this;
    }

    /**
     * Get premium
     *
     * @return boolean $premium
     */
    public function getPremium()
    {
        return $this->premium;
    }

    /**
     * Set emailValidationHash
     *
     * @param string $emailValidationHash
     * @return self
     */
    public function setEmailValidationHash($emailValidationHash)
    {
        $this->emailValidationHash = $emailValidationHash;
        return $this;
    }

    /**
     * Get emailValidationHash
     *
     * @return string $emailValidationHash
     */
    public function getEmailValidationHash()
    {
        return $this->emailValidationHash;
    }

    /**
     * Set iosApps
     *
     * @param collection $iosApps
     * @return self
     */
    public function setIosApps($iosApps)
    {
        $this->iosApps = $iosApps;
        return $this;
    }

    /**
     * Get iosApps
     *
     * @return collection $iosApps
     */
    public function getIosApps()
    {
        return $this->iosApps;
    }

    /**
     * Set androidApps
     *
     * @param collection $androidApps
     * @return self
     */
    public function setAndroidApps($androidApps)
    {
        $this->androidApps = $androidApps;
        return $this;
    }

    /**
     * Get androidApps
     *
     * @return collection $androidApps
     */
    public function getAndroidApps()
    {
        return $this->androidApps;
    }

    /**
     * Set categories
     *
     * @param collection $categories
     * @return self
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * Get categories
     *
     * @return collection $categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set languages
     *
     * @param collection $languages
     * @return self
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;
        return $this;
    }

    /**
     * Get languages
     *
     * @return collection $languages
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Set followers
     *
     * @param collection $followers
     * @return self
     */
    public function setFollowers($followers)
    {
        $this->followers = $followers;
        return $this;
    }

    /**
     * Get followers
     *
     * @return collection $followers
     */
    public function getFollowers()
    {
        return $this->followers;
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
     * Sets file.
     *
     * @param UploadedFile $file
     */
    /*public function setFile(UploadedFile $file = null, $username)
    {
        $name = $username.'.jpg';
        $file->move(
            $this->getUploadRootDir(),
            $name
        );
        $avatar = '/'.$this->getUploadDir().'/'.$name;
        $this->avatar = $avatar;
        return $avatar;
    }*/

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    /*public function getFile()
    {
        return $this->file;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/profile/avatars';
    }*/

    /**
     * Set paymentTerms
     *
     * @param collection $paymentTerms
     * @return self
     */
    public function setPaymentTerms($paymentTerms)
    {
        $this->paymentTerms = $paymentTerms;
        return $this;
    }

    /**
     * Get paymentTerms
     *
     * @return collection $paymentTerms
     */
    public function getPaymentTerms()
    {
        return $this->paymentTerms;
    }

    /**
     * Set paymentMethods
     *
     * @param collection $paymentMethods
     * @return self
     */
    public function setPaymentMethods($paymentMethods)
    {
        $this->paymentMethods = $paymentMethods;
        return $this;
    }

    /**
     * Get paymentMethods
     *
     * @return collection $paymentMethods
     */
    public function getPaymentMethods()
    {
        return $this->paymentMethods;
    }

    /**
     * Set otherCompanySubType
     *
     * @param string $otherCompanySubType
     * @return self
     */
    public function setOtherCompanySubType($otherCompanySubType)
    {
        $this->otherCompanySubType = $otherCompanySubType;
        return $this;
    }

    /**
     * Get otherCompanySubType
     *
     * @return string $otherCompanySubType
     */
    public function getOtherCompanySubType()
    {
        return $this->otherCompanySubType;
    }

    /**
     * Set trackingServices
     *
     * @param collection $trackingServices
     * @return self
     */
    public function setTrackingServices($trackingServices)
    {
        $this->trackingServices = $trackingServices;
        return $this;
    }

    /**
     * Get trackingServices
     *
     * @return collection $trackingServices
     */
    public function getTrackingServices()
    {
        return $this->trackingServices;
    }

    /**
     * Set cover
     *
     * @param string $cover
     * @return self
     */
    public function setCover($cover)
    {
        $this->cover = $cover;
        return $this;
    }

    /**
     * Get cover
     *
     * @return string $cover
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set settings
     *
     * @param collection $settings
     * @return self
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
        return $this;
    }

    /**
     * Get settings
     *
     * @return collection $settings
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Set contactEmail
     *
     * @param string $contactEmail
     * @return self
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;
        return $this;
    }

    /**
     * Get contactEmail
     *
     * @return string $contactEmail
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * Set birthdayMM
     *
     * @param string $birthdayMM
     * @return self
     */
    public function setBirthdayMM($birthdayMM)
    {
        $this->birthdayMM = $birthdayMM;
        return $this;
    }

    /**
     * Get birthdayMM
     *
     * @return string $birthdayMM
     */
    public function getBirthdayMM()
    {
        return $this->birthdayMM;
    }

    /**
     * Set birthdayDD
     *
     * @param string $birthdayDD
     * @return self
     */
    public function setBirthdayDD($birthdayDD)
    {
        $this->birthdayDD = $birthdayDD;
        return $this;
    }

    /**
     * Get birthdayDD
     *
     * @return string $birthdayDD
     */
    public function getBirthdayDD()
    {
        return $this->birthdayDD;
    }

    /**
     * Set birthdayYYYY
     *
     * @param string $birthdayYYYY
     * @return self
     */
    public function setBirthdayYYYY($birthdayYYYY)
    {
        $this->birthdayYYYY = $birthdayYYYY;
        return $this;
    }

    /**
     * Get birthdayYYYY
     *
     * @return string $birthdayYYYY
     */
    public function getBirthdayYYYY()
    {
        return $this->birthdayYYYY;
    }

    /**
     * Set companyPage
     *
     * @param collection $companyPage
     * @return self
     */
    public function setCompanyPage($companyPage)
    {
        $this->companyPage = $companyPage;
        return $this;
    }

    /**
     * Get companyPage
     *
     * @return collection $companyPage
     */
    public function getCompanyPage()
    {
        return $this->companyPage;
    }

    /**
     * Set responseRate
     *
     * @param float $responseRate
     * @return self
     */
    public function setResponseRate($responseRate)
    {
        $this->responseRate = $responseRate;
        return $this;
    }

    /**
     * Get responseRate
     *
     * @return float $responseRate
     */
    public function getResponseRate()
    {
        return $this->responseRate;
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

    /**
     * Set emailsNotifications
     *
     * @param int $emailsNotifications
     * @return self
     */
    public function setEmailsNotifications($emailsNotifications)
    {
        $this->emailsNotifications = $emailsNotifications;
        return $this;
    }

    /**
     * Get emailsNotifications
     *
     * @return int $emailsNotifications
     */
    public function getEmailsNotifications()
    {
        return $this->emailsNotifications;
    }

    /**
     * Set totalReceivedEmails
     *
     * @param int $totalReceivedEmails
     * @return self
     */
    public function setTotalReceivedEmails($totalReceivedEmails)
    {
        $this->totalReceivedEmails = $totalReceivedEmails;
        return $this;
    }

    /**
     * Get totalReceivedEmails
     *
     * @return int $totalReceivedEmails
     */
    public function getTotalReceivedEmails()
    {
        return $this->totalReceivedEmails;
    }

    /**
     * Set totalSentEmails
     *
     * @param int $totalSentEmails
     * @return self
     */
    public function setTotalSentEmails($totalSentEmails)
    {
        $this->totalSentEmails = $totalSentEmails;
        return $this;
    }

    /**
     * Get totalSentEmails
     *
     * @return int $totalSentEmails
     */
    public function getTotalSentEmails()
    {
        return $this->totalSentEmails;
    }

    /**
     * Set totalRepliedEmails
     *
     * @param int $totalRepliedEmails
     * @return self
     */
    public function setTotalRepliedEmails($totalRepliedEmails)
    {
        $this->totalRepliedEmails = $totalRepliedEmails;
        return $this;
    }

    /**
     * Get totalRepliedEmails
     *
     * @return int $totalRepliedEmails
     */
    public function getTotalRepliedEmails()
    {
        return $this->totalRepliedEmails;
    }

    /**
     * Set whoVisitedMe
     *
     * @param collection $whoVisitedMe
     * @return self
     */
    public function setWhoVisitedMe($whoVisitedMe)
    {
        $this->whoVisitedMe = $whoVisitedMe;
        return $this;
    }

    /**
     * Get whoVisitedMe
     *
     * @return collection $whoVisitedMe
     */
    public function getWhoVisitedMe()
    {
        return $this->whoVisitedMe;
    }

    /**
     * Set iVisited
     *
     * @param collection $iVisited
     * @return self
     */
    public function setIVisited($iVisited)
    {
        $this->iVisited = $iVisited;
        return $this;
    }

    /**
     * Get iVisited
     *
     * @return collection $iVisited
     */
    public function getIVisited()
    {
        return $this->iVisited;
    }

    /**
     * Set inTouch
     *
     * @param collection $inTouch
     * @return self
     */
    public function setInTouch($inTouch)
    {
        $this->inTouch = $inTouch;
        return $this;
    }

    /**
     * Get inTouch
     *
     * @return collection $inTouch
     */
    public function getInTouch()
    {
        return $this->inTouch;
    }

    /**
     * Set inTouchCounter
     *
     * @param int $inTouchCounter
     * @return self
     */
    public function setInTouchCounter($inTouchCounter)
    {
        $this->inTouchCounter = $inTouchCounter;
        return $this;
    }

    /**
     * Get inTouchCounter
     *
     * @return int $inTouchCounter
     */
    public function getInTouchCounter()
    {
        return $this->inTouchCounter;
    }

    /**
     * Set alerts
     *
     * @param collection $alerts
     * @return self
     */
    public function setAlerts($alerts)
    {
        $this->alerts = $alerts;
        return $this;
    }

    /**
     * Get alerts
     *
     * @return collection $alerts
     */
    public function getAlerts()
    {
        return $this->alerts;
    }

    /**
     * Set alertsNotifications
     *
     * @param int $alertsNotifications
     * @return self
     */
    public function setAlertsNotifications($alertsNotifications)
    {
        $this->alertsNotifications = $alertsNotifications;
        return $this;
    }

    /**
     * Get alertsNotifications
     *
     * @return int $alertsNotifications
     */
    public function getAlertsNotifications()
    {
        return $this->alertsNotifications;
    }

    /**
     * Set privacyHidden
     *
     * @param boolean $privacyHidden
     * @return self
     */
    public function setPrivacyHidden($privacyHidden)
    {
        $this->privacyHidden = $privacyHidden;
        return $this;
    }

    /**
     * Get privacyHidden
     *
     * @return boolean $privacyHidden
     */
    public function getPrivacyHidden()
    {
        return $this->privacyHidden;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return self
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get token
     *
     * @return string $token
     */
    public function getToken()
    {
        return $this->token;
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

    /**
     * Set version
     *
     * @param string $version
     * @return self
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * Get version
     *
     * @return string $version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return self
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * Get summary
     *
     * @return string $summary
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set linkedInAccessToken
     *
     * @param string $linkedInAccessToken
     * @return self
     */
    public function setLinkedInAccessToken($linkedInAccessToken)
    {
        $this->linkedInAccessToken = $linkedInAccessToken;
        return $this;
    }

    /**
     * Get linkedInAccessToken
     *
     * @return string $linkedInAccessToken
     */
    public function getLinkedInAccessToken()
    {
        return $this->linkedInAccessToken;
    }

    /**
     * Set linkedInInvites
     *
     * @param collection $linkedInInvites
     * @return self
     */
    public function setLinkedInInvites($linkedInInvites)
    {
        $this->linkedInInvites = $linkedInInvites;
        return $this;
    }

    /**
     * Get linkedInInvites
     *
     * @return collection $linkedInInvites
     */
    public function getLinkedInInvites()
    {
        return $this->linkedInInvites;
    }

    /**
     * Set linkedinID
     *
     * @param string $linkedinID
     * @return self
     */
    public function setLinkedinID($linkedinID)
    {
        $this->linkedinID = $linkedinID;
        return $this;
    }

    /**
     * Get linkedinID
     *
     * @return string $linkedinID
     */
    public function getLinkedinID()
    {
        return $this->linkedinID;
    }

    /**
     * Set linkedInAccessTokenDate
     *
     * @param string $linkedInAccessTokenDate
     * @return self
     */
    public function setLinkedInAccessTokenDate($linkedInAccessTokenDate)
    {
        $this->linkedInAccessTokenDate = $linkedInAccessTokenDate;
        return $this;
    }

    /**
     * Get linkedInAccessTokenDate
     *
     * @return string $linkedInAccessTokenDate
     */
    public function getLinkedInAccessTokenDate()
    {
        return $this->linkedInAccessTokenDate;
    }

    /**
     * Set hasSyncLinkedin
     *
     * @param boolean $hasSyncLinkedin
     * @return self
     */
    public function setHasSyncLinkedin($hasSyncLinkedin)
    {
        $this->hasSyncLinkedin = $hasSyncLinkedin;
        return $this;
    }

    /**
     * Get hasSyncLinkedin
     *
     * @return boolean $hasSyncLinkedin
     */
    public function getHasSyncLinkedin()
    {
        return $this->hasSyncLinkedin;
    }


    /**
     * Set hasVisitedOwnProfile
     *
     * @param boolean $hasVisitedOwnProfile
     * @return self
     */
    public function setHasVisitedOwnProfile($hasVisitedOwnProfile)
    {
        $this->hasVisitedOwnProfile = $hasVisitedOwnProfile;
        return $this;
    }

    /**
     * Get hasVisitedOwnProfile
     *
     * @return boolean $hasVisitedOwnProfile
     */
    public function getHasVisitedOwnProfile()
    {
        return $this->hasVisitedOwnProfile;
    }

    /**
     * Set linkedInCompanies
     *
     * @param collection $linkedInCompanies
     * @return self
     */
    public function setLinkedInCompanies($linkedInCompanies)
    {
        $this->linkedInCompanies = $linkedInCompanies;
        return $this;
    }

    /**
     * Get linkedInCompanies
     *
     * @return collection $linkedInCompanies
     */
    public function getLinkedInCompanies()
    {
        return $this->linkedInCompanies;
    }

    /**
     * Set linkedInCompaniesID
     *
     * @param collection $linkedInCompaniesID
     * @return self
     */
    public function setLinkedInCompaniesID($linkedInCompaniesID)
    {
        $this->linkedInCompaniesID = $linkedInCompaniesID;
        return $this;
    }

    /**
     * Get linkedInCompaniesID
     *
     * @return collection $linkedInCompaniesID
     */
    public function getLinkedInCompaniesID()
    {
        return $this->linkedInCompaniesID;
    }

    /**
     * Set hasEditedOwnProfile
     *
     * @param boolean $hasEditedOwnProfile
     * @return self
     */
    public function setHasEditedOwnProfile($hasEditedOwnProfile)
    {
        $this->hasEditedOwnProfile = $hasEditedOwnProfile;
        return $this;
    }

    /**
     * Get hasEditedOwnProfile
     *
     * @return boolean $hasEditedOwnProfile
     */
    public function getHasEditedOwnProfile()
    {
        return $this->hasEditedOwnProfile;
    }
}
