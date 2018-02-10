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
class Mail {

    /**
     * @MongoDB\Id(strategy="auto")
     */
    public $id;

    /**
     * @MongoDB\String
     */
    public $subject;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $content;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $attachments;

    /**
     * @MongoDB\String
     */
    public $date;

    /**
     * @MongoDB\String
     */
    public $updateDate;

    /**
     * @MongoDB\String
     */
    public $fromID;

    /**
     * @MongoDB\String
     */
    public $fromCurrentID;

    /**
     * @MongoDB\String
     */
    public $from;

    /**
     * @MongoDB\String
     */
    public $senderName;

    /**
     * @MongoDB\String
     */
    public $receiverName;

    /**
     * @MongoDB\String
     */
    public $senderCompany;

    /**
     * @MongoDB\String
     */
    public $receiverCompany;

    /**
     * @MongoDB\String
     */
    public $senderJobTitle;

    /**
     * @MongoDB\String
     */
    public $receiverJobTitle;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $to;

    /**
     * @MongoDB\String
     */
    public $toID;

    /**
     * @MongoDB\String
     */
    public $toCurrentID;

    /**
     * @MongoDB\String
     */
    public $senderAvatar;

    /**
     * @MongoDB\String
     */
    public $receiverAvatar;

    /**
     * @MongoDB\String
     */
    //public $senderUsername;

    /**
     * @MongoDB\String
     */
    //public $receiverUsername;

    /**
     * @MongoDB\Collection(strategy="pushAll")
     */
    public $mails;

    /**
     * @MongoDB\Boolean
     */
    public $read;

    /**
     * @MongoDB\Boolean
     */
    public $rated;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return self
     */
    public function setSubject($subject) {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Get subject
     *
     * @return string $subject
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * Set content
     *
     * @param collection $content
     * @return self
     */
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return collection $content
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set attachments
     *
     * @param collection $attachments
     * @return self
     */
    public function setAttachments($attachments) {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * Get attachments
     *
     * @return collection $attachments
     */
    public function getAttachments() {
        return $this->attachments;
    }

    /**
     * Set date
     *
     * @param string $date
     * @return self
     */
    public function setDate($date) {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return string $date
     */
    public function getDate() {
        return $this->date;
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
     * Set fromID
     *
     * @param string $fromID
     * @return self
     */
    public function setFromID($fromID) {
        $this->fromID = $fromID;
        return $this;
    }

    /**
     * Get fromID
     *
     * @return string $fromID
     */
    public function getFromID() {
        return $this->fromID;
    }

    /**
     * Set fromCurrentID
     *
     * @param string $fromCurrentID
     * @return self
     */
    public function setFromCurrentID($fromCurrentID) {
        $this->fromCurrentID = $fromCurrentID;
        return $this;
    }

    /**
     * Get fromCurrentID
     *
     * @return string $fromCurrentID
     */
    public function getFromCurrentID() {
        return $this->fromCurrentID;
    }

    /**
     * Set from
     *
     * @param string $from
     * @return self
     */
    public function setFrom($from) {
        $this->from = $from;
        return $this;
    }

    /**
     * Get from
     *
     * @return string $from
     */
    public function getFrom() {
        return $this->from;
    }

    /**
     * Set senderName
     *
     * @param string $senderName
     * @return self
     */
    public function setSenderName($senderName) {
        $this->senderName = $senderName;
        return $this;
    }

    /**
     * Get senderName
     *
     * @return string $senderName
     */
    public function getSenderName() {
        return $this->senderName;
    }

    /**
     * Set receiverName
     *
     * @param string $receiverName
     * @return self
     */
    public function setReceiverName($receiverName) {
        $this->receiverName = $receiverName;
        return $this;
    }

    /**
     * Get receiverName
     *
     * @return string $receiverName
     */
    public function getReceiverName() {
        return $this->receiverName;
    }

    /**
     * Set senderCompany
     *
     * @param string $senderCompany
     * @return self
     */
    public function setSenderCompany($senderCompany) {
        $this->senderCompany = $senderCompany;
        return $this;
    }

    /**
     * Get senderCompany
     *
     * @return string $senderCompany
     */
    public function getSenderCompany() {
        return $this->senderCompany;
    }

    /**
     * Set receiverCompany
     *
     * @param string $receiverCompany
     * @return self
     */
    public function setReceiverCompany($receiverCompany) {
        $this->receiverCompany = $receiverCompany;
        return $this;
    }

    /**
     * Get receiverCompany
     *
     * @return string $receiverCompany
     */
    public function getReceiverCompany() {
        return $this->receiverCompany;
    }

    /**
     * Set senderJobTitle
     *
     * @param string $senderJobTitle
     * @return self
     */
    public function setSenderJobTitle($senderJobTitle) {
        $this->senderJobTitle = $senderJobTitle;
        return $this;
    }

    /**
     * Get senderJobTitle
     *
     * @return string $senderJobTitle
     */
    public function getSenderJobTitle() {
        return $this->senderJobTitle;
    }

    /**
     * Set receiverJobTitle
     *
     * @param string $receiverJobTitle
     * @return self
     */
    public function setReceiverJobTitle($receiverJobTitle) {
        $this->receiverJobTitle = $receiverJobTitle;
        return $this;
    }

    /**
     * Get receiverJobTitle
     *
     * @return string $receiverJobTitle
     */
    public function getReceiverJobTitle() {
        return $this->receiverJobTitle;
    }

    /**
     * Set to
     *
     * @param collection $to
     * @return self
     */
    public function setTo($to) {
        $this->to = $to;
        return $this;
    }

    /**
     * Get to
     *
     * @return collection $to
     */
    public function getTo() {
        return $this->to;
    }

    /**
     * Set toID
     *
     * @param string $toID
     * @return self
     */
    public function setToID($toID) {
        $this->toID = $toID;
        return $this;
    }

    /**
     * Get toID
     *
     * @return string $toID
     */
    public function getToID() {
        return $this->toID;
    }

    /**
     * Set toCurrentID
     *
     * @param string $toCurrentID
     * @return self
     */
    public function setToCurrentID($toCurrentID) {
        $this->toCurrentID = $toCurrentID;
        return $this;
    }

    /**
     * Get toCurrentID
     *
     * @return string $toCurrentID
     */
    public function getToCurrentID() {
        return $this->toCurrentID;
    }

    /**
     * Set senderAvatar
     *
     * @param string $senderAvatar
     * @return self
     */
    public function setSenderAvatar($senderAvatar) {
        $this->senderAvatar = $senderAvatar;
        return $this;
    }

    /**
     * Get senderAvatar
     *
     * @return string $senderAvatar
     */
    public function getSenderAvatar() {
        return $this->senderAvatar;
    }

    /**
     * Set receiverAvatar
     *
     * @param string $receiverAvatar
     * @return self
     */
    public function setReceiverAvatar($receiverAvatar) {
        $this->receiverAvatar = $receiverAvatar;
        return $this;
    }

    /**
     * Get receiverAvatar
     *
     * @return string $receiverAvatar
     */
    public function getReceiverAvatar() {
        return $this->receiverAvatar;
    }

    /**
     * Set mails
     *
     * @param collection $mails
     * @return self
     */
    public function setMails($mails) {
        $this->mails = $mails;
        return $this;
    }

    /**
     * Get mails
     *
     * @return collection $mails
     */
    public function getMails() {
        return $this->mails;
    }

    /**
     * Set read
     *
     * @param boolean $read
     * @return self
     */
    public function setRead($read) {
        $this->read = $read;
        return $this;
    }

    /**
     * Get read
     *
     * @return boolean $read
     */
    public function getRead() {
        return $this->read;
    }

    /**
     * Set rated
     *
     * @param boolean $rated
     * @return self
     */
    public function setRated($rated) {
        $this->rated = $rated;
        return $this;
    }

    /**
     * Get rated
     *
     * @return boolean $rated
     */
    public function getRated() {
        return $this->rated;
    }

}
