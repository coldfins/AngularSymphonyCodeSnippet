<?php
/**
 * Created by PhpStorm.
 * User: josepmarti
 * Date: 26/05/14
 * Time: 10:52
 */

namespace Mobntouch\DataBaseBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Filesystem\Filesystem;

class AutomaticOffersCloseCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('offers:automatic_close')
            ->setDescription('Automatic Offers Close Command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set("memory_limit", "-1");
        set_time_limit(0);

        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');

        $now = time();
        $alertTime = strtotime("+6 hours");
        //$alertTime = strtotime("+1 days");

        // ALERTING OWNER
        // NOTIFY OFFER'S OWNER - 1 DAY BEFORE CHANGING STATUS TO CLOSE
        // Commeneted on 20-11-2017
        /*$offers = $dm->createQueryBuilder('DataBaseBundle:Offer')
            ->field('status')->equals('LIVE')
            ->field('closeOfferEmailNotification')->equals(false)
            ->field('expiryTimestamp')->lte($alertTime)
            ->getQuery()
            ->execute();

        print_r("\n");

        foreach($offers as $offer){

            print_r("ALERTING OWNER:\n");
            //print_r($offer->getId());
            //print_r("Day: $alertTime\n");
            //print_r("Expiry: ".$offer->getExpiryDate()."\n");
            //print_r("Diff: ".($alertTime-$offer->getExpiryTimestamp())."\n");

            $owner = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('_id')->equals($offer->getUserID())
                ->getQuery()
                ->getSingleResult();

            if($owner) {

                print_r($owner->getUsername());
                $ownerSettings = $owner->getSettings();
                if(isset($ownerSettings['notifications']) and isset($ownerSettings['notifications'][0])  and isset($ownerSettings['notifications'][0]['email_offeralertduedate'])  and $ownerSettings['notifications'][0]['email_offeralertduedate']) {
                    //$this->sendAlertEmail($owner, $offer); //Commented on 20-11-2017
                }else print_r('SETTINGS OFF');

                // update DATABASE
                $dm->createQueryBuilder('DataBaseBundle:Offer')
                    // Find the Offer
                    ->update()
                    ->multiple(false)
                    ->field('_id')->equals($offer->getId())
                    ->field("userID")->equals($owner->getId())
                    ->field("username")->equals($owner->getUsername())

                    // UPDATE
                    ->field("updateDate")->set(time())
                    ->field("closeOfferEmailNotification")->set(true)

                    // Options
                    ->upsert(false)
                    ->getQuery()
                    ->execute();

            }

        }

        // CHANGING OFFER STATUS
        // NOTIFY EVERYONE NEW STATUS = CLOSE
        $offers = $dm->createQueryBuilder('DataBaseBundle:Offer')
            ->field('status')->equals('LIVE')
            ->field('closeOfferEmailNotification')->equals(true)
            ->field('expiryTimestamp')->lte($now)
            ->getQuery()
            ->execute();

        foreach($offers as $offer){
            print_r("\n");
            print_r("\n");
            print_r("CHANGING STATUS:\n");
            print_r($offer->getId());
            print_r("\n");
            print_r("Now: $now\n");
            print_r("Expiry: ".$offer->getExpiryTimestamp()."\n");
            print_r("\n");

            $owner = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('_id')->equals($offer->getUserID())
                ->getQuery()
                ->getSingleResult();

            if($owner) {
                print_r($owner->getUsername());
                // CLOSE THE OFFER AND NOTIFY THE USERS WHO REPLIED TO THIS OFFER
                $this->closeOffer($offer, $owner);

                // NOTIFY THE OWNER BY EMAIL
                $ownerSettings = $owner->getSettings();
                if(isset($ownerSettings['notifications']) and isset($ownerSettings['notifications'][0])  and isset($ownerSettings['notifications'][0]['email_offerclosed'])  and $ownerSettings['notifications'][0]['email_offerclosed']) {
                    $this->sendCloseConfirmationEmail($offer, $owner);
                }

                $replies = $dm->createQueryBuilder('DataBaseBundle:OfferReply')
                    ->field('offerID')->equals($offer->getId())
                    ->getQuery()
                    ->execute();

                if($replies){

                    foreach($replies as $reply){

                        $repliedUser = $dm->createQueryBuilder('DataBaseBundle:User')
                            ->field('_id')->equals($reply->getUserID())
                            ->field('settings.notifications.0.email_replyclosed')->equals(true)
                            ->getQuery()
                            ->getSingleResult();

                        if($repliedUser) {

                            print_r("Replies User:\n");
                            print_r($repliedUser->getUsername());
                            // NOTIFY EVERY USER WHO REPLIED TO THIS OFFER
                            $this->sendClosedReplyEmail($offer, $repliedUser, $owner);
                        }

                    }
                }
            }

        }*/


    }

    private function sendAlertEmail($user, $offer){

        print_r("\nSENDING ALERT!\n");

        switch($this->getContainer()->getParameter('kernel.environment')){
            case 'dev':
                $baseLink = 'http://angular.dev/offers/details/';
                break;
            case 'test':
                $baseLink = 'http://angular.dev/offers/details/';
                break;
            case 'adhoc':
                $baseLink = 'https://www-dev.mobintouch.com/offers/details/';
                break;
            case 'prod':
            default:
                $baseLink = 'https://www.mobintouch.com/offers/details/';
                break;
        }

        $link = $baseLink.$offer->getId();

        $message = \Swift_Message::newInstance()
            ->setSubject('Offer: Closing Alert')
            ->setFrom(array('noreply@mobintouch.com'=> 'Mobintouch Offers'))
            ->setTo($user->getEmail())
            ->setContentType("text/html")
            ->setBody(
                $this->getContainer()->get('templating')->render(
                    'APIBundle:Mail:offerCloseAlert.html.twig',
                    array('title' => 'Mobintouch offers report', 'user' => $user, 'link' => $link, 'unsubcribe' => 1, 'offer' => $offer)
                )
            )
        ;
        $this->getContainer()->get('mailer')->send($message);
    }

    private function sendCloseConfirmationEmail($offer, $user){

        switch($this->getContainer()->getParameter('kernel.environment')){
            case 'dev':
                $baseLink = 'http://angular.dev/offers/details/';
                break;
            case 'test':
                $baseLink = 'http://angular.dev/offers/details/';
                break;
            case 'adhoc':
                $baseLink = 'https://www-dev.mobintouch.com/offers/details/';
                break;
            case 'prod':
            default:
                $baseLink = 'https://www.mobintouch.com/offers/details/';
                break;
        }

        $link = $baseLink.$offer->getId();

        $message = \Swift_Message::newInstance()
            ->setSubject('Offer: Has Been Closed')
            ->setFrom(array('noreply@mobintouch.com'=> 'Mobintouch Offers'))
            ->setTo($user->getEmail())
            ->setContentType("text/html")
            ->setBody(
                $this->getContainer()->get('templating')->render(
                    'APIBundle:Mail:offerClose.html.twig',
                    array('title' => 'Mobintouch offers report', 'user' => $user, 'link' => $link, 'unsubcribe' => 1, 'offer' => $offer)
                )
            )
        ;
        $this->getContainer()->get('mailer')->send($message);
    }
    private function sendClosedReplyEmail($offer, $user, $owner){

        switch($this->getContainer()->getParameter('kernel.environment')){
            case 'dev':
                $baseLink = 'http://angular.dev/offers/';
                break;
            case 'test':
                $baseLink = 'http://angular.dev/offers/';
                break;
            case 'adhoc':
                $baseLink = 'https://www-dev.mobintouch.com/offers/';
                break;
            case 'prod':
            default:
                $baseLink = 'https://www.mobintouch.com/offers/';
                break;
        }

        $link = $baseLink.'details/'.$offer->getId();

        $isEngaged = false;
        $repliedOffers = $user->getRepliedOffers();
        foreach($repliedOffers as $of){
            if($of['offerID']==$offer->getId()){
                if($of['counter']>0) $isEngaged = true;
                break;
            }
        }
        if(!$isEngaged) $link = $baseLink.'myreplies';

        $message = \Swift_Message::newInstance()
            ->setSubject('Offer: Has Been Closed')
            ->setFrom(array('noreply@mobintouch.com'=> 'Mobintouch Offers'))
            ->setTo($user->getEmail())
            ->setContentType("text/html")
            ->setBody(
                $this->getContainer()->get('templating')->render(
                    'APIBundle:Mail:offerClosedReply.html.twig',
                    array('title' => 'Mobintouch offers report', 'user' => $user, 'owner' => $owner, 'link' => $link, 'unsubcribe' => 1, 'offer' => $offer)
                )
            )
        ;
        $this->getContainer()->get('mailer')->send($message);
    }

    private function closeOffer($offer, $user){

        $offerID = $offer->getId();

        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');

        // update DATABASE
        $dm->createQueryBuilder('DataBaseBundle:Offer')
            // Find the Offer
            ->update()
            ->multiple(false)
            ->field('_id')->equals($offerID)
            ->field("userID")->equals($user->getId())
            ->field("username")->equals($user->getUsername())

            ->field("updateDate")->set(time())
            ->field("status")->set('CLOSED')

            // Options
            ->upsert(false)
            ->getQuery()
            ->execute();

        // update DATABASE
        $dm->createQueryBuilder('DataBaseBundle:User')
            // Find the Campaign
            ->update()
            ->multiple(false)
            ->field('_id')->equals($user->getId())
            // Update found Campaign
            ->field('alertsNotifications')->inc(1)
            ->field('alerts')->push(array('$each' => array(array(
                'id' => $offerID . time(),
                'type' => 9, // 9 = offer details, 10 = myreplies
                'read' => false,
                'action' => 'your offer: '.$offer->getIdentifier().' is now closed',
                'username' => $offerID,
                'name' => $user->getName(),
                'lastname' => $user->getLastname(),
                'avatar' => $user->getAvatar(),
                'date' => time() * 1000
            )), '$slice' => -90))
            ->field('updateDate')->set(time())
            // Options
            ->upsert(false)
            ->getQuery()
            ->execute();

        $myreplies = array();
        // GET REPLIES
        $qr = $dm->createQueryBuilder('DataBaseBundle:OfferReply')
            //->field('status', 'LIVE')
            ->field("offerID")->equals($offerID)
            ->getQuery()
            ->execute();
        if($qr){
            foreach($qr as $o){
                $myreplies[] = $o->getUserID();
            }

            // GET OFFERS
            $q = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field("_id")->in($myreplies)
                ->hydrate(false)
                ->getQuery()
                ->execute();
            if($q){
                foreach($q as $replyUser) {

                    $userID = $replyUser['_id']->{'$id'};

                    $isEngaged = false;
                    $repliedOffers = $replyUser['repliedOffers'];
                    foreach($repliedOffers as $of){
                        if($of['offerID']==$offerID){
                            if($of['counter']>0) $isEngaged = true;
                            break;
                        }
                    }

                    // update DATABASE
                    $dm->createQueryBuilder('DataBaseBundle:User')
                        // Find the Campaign
                        ->update()
                        ->multiple(false)
                        ->field('_id')->equals($userID)
                        // Update found Campaign
                        ->field('alertsNotifications')->inc(1)
                        ->field('alerts')->push(array('$each' => array(array(
                            'id' => $offerID . time(),
                            'type' => $isEngaged?9:11, // 9 = offer details, 10 = myreplies
                            'read' => false,
                            'action' => 'closed the offer: '.$offer->getIdentifier(),
                            'username' => $offerID,
                            'name' => $user->getName(),
                            'lastname' => $user->getLastname(),
                            'avatar' => $user->getAvatar(),
                            'date' => time() * 1000
                        )), '$slice' => -90))
                        ->field('updateDate')->set(time())
                        // Options
                        ->upsert(false)
                        ->getQuery()
                        ->execute();
                }
            }
        }

        $dm->flush();
        $dm->clear(); // Detaches all objects from Doctrine!
    }


}

