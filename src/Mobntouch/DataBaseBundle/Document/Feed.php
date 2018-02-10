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
class Feed {

    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    public $newsID;
    
    /**
     * @MongoDB\String
     */
    public $sourceID;

    /**
     * @MongoDB\String
     */
    public $title;

    /**
     * @MongoDB\String
     */
    public $summary;

    /**
     * @MongoDB\String
     */
    public $image;

    /**
     * @MongoDB\String
     */
    public $source;
    
    /**
     * @MongoDB\String
     */
    public $sourceURL;

    /**
     * @MongoDB\String
     */
    public $url;

    /**
     * @MongoDB\String
     */
    public $date;




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
     * Set title
     *
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
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
     * Set image
     *
     * @param string $image
     * @return self
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     *
     * @return string $image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
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
     * Set source
     *
     * @param string $source
     * @return self
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Get source
     *
     * @return string $source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set sourceURL
     *
     * @param string $sourceURL
     * @return self
     */
    public function setSourceURL($sourceURL)
    {
        $this->sourceURL = $sourceURL;
        return $this;
    }

    /**
     * Get sourceURL
     *
     * @return string $sourceURL
     */
    public function getSourceURL()
    {
        return $this->sourceURL;
    }

    /**
     * Set newsID
     *
     * @param string $newsID
     * @return self
     */
    public function setNewsID($newsID)
    {
        $this->newsID = $newsID;
        return $this;
    }

    /**
     * Get newsID
     *
     * @return string $newsID
     */
    public function getNewsID()
    {
        return $this->newsID;
    }

    /**
     * Set sourceID
     *
     * @param string $sourceID
     * @return self
     */
    public function setSourceID($sourceID)
    {
        $this->sourceID = $sourceID;
        return $this;
    }

    /**
     * Get sourceID
     *
     * @return string $sourceID
     */
    public function getSourceID()
    {
        return $this->sourceID;
    }
}
