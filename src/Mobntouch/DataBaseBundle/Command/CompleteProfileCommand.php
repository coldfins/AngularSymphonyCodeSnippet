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

class CompleteProfileCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('complete:profile')
                ->setDescription('Complete Profile');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');

        $count = 0;
        $skip = 0;
        $limit = 10;

        $total = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('validated')->equals(true)
                ->field('settings.notifications.0.email_fillprofile')->equals(true)
                ->field('buyTraffic.valid')->exists(false)
                ->field('sellTraffic.valid')->exists(false)
                ->getQuery()
                ->execute()
                ->count();

        print_r("TOTAL:\n");
        print_r($total);
        print_r("\n");

        //For user profile update

        do {

            $users = $dm->createQueryBuilder('DataBaseBundle:User')
                    ->field('validated')->equals(true)
                    ->field('settings.notifications.0.email_fillprofile')->equals(true)
                    ->field('buyTraffic.valid')->exists(false)
                    ->field('sellTraffic.valid')->exists(false)
                    ->limit($limit)
                    ->skip($limit * $skip)
                    ->getQuery()
                    ->execute();

            foreach ($users as $user) {
                print_r("\n");
                $count++;
                //$this->sendEmail($user); //by jignesh on 20/11/2017
                // update DATABASE
                $alertsNotifications = (int) $user->getAlertsNotifications() + 1;
                //Commented on 20-11-2017 to remove complete profile alert
                /*$dm->createQueryBuilder('DataBaseBundle:User')
                        // Find the Campaign
                        ->update()
                        ->multiple(false)
                        ->field('_id')->equals($user->getId())

                        // Update found Campaign
                        ->field('alertsNotifications')->set($alertsNotifications)
                        ->field('alerts')->push(array('$each' => array(array(
                                    'id' => $user->getId() . time(),
                                    'type' => 5,
                                    'read' => false,
                                    'action' => 'Complete the sections buying/selling traffic and boost your visibility!',
                                    'username' => $user->getUsername(),
                                    'name' => $user->getName(),
                                    'lastname' => $user->getLastname(),
                                    'avatar' => $user->getAvatar(),
                                    'date' => time() * 1000
                                )), '$slice' => -90))
                        ->field('updateDate')->set(time())


                        // Options
                        ->upsert(true)
                        ->getQuery()
                        ->execute();*/
            }

            $skip++;
        } while ($count < $total);



        $count = 0;
        $skip = 0;
        $limit = 10;

        $dm->createQueryBuilder('DataBaseBundle:Company')
                ->update()
                ->multiple(true)
                ->field('companyPoints')->unsetField()->exists(true)
                ->upsert(false)
                ->getQuery()
                ->execute();


        $totalCompanies = $dm->createQueryBuilder('DataBaseBundle:Company')
                ->getQuery()
                ->execute()
                ->count();

        print_r("TOTAL COMPANIES:\n");
        print_r($totalCompanies);
        print_r("\n");

        do {

            $companies = $dm->createQueryBuilder('DataBaseBundle:Company')
                    ->limit($limit)
                    ->skip($limit * $skip)
                    ->getQuery()
                    ->execute();

            foreach ($companies as $company) {
                $count++;

                //
                $percentage = 0;
                $points = 0;
                $followerTotal = 0;
                //

                $date = time();
                if (date('l', $date) == "Sunday") {
                    $date = $date - 86400;
                }
                $start_day_week = strtotime("-30 days", $date);
                $end_day_week = strtotime("-1 days", $date);

                if ($company->getCompanyType() == "Advertiser") {
                    if ($company->getUsername()) {
                        $q1 = $dm->createQueryBuilder('DataBaseBundle:Update')
                                ->field('companyUsername')->equals($company->getUsername())
                                ->field('type')->equals(5)
                                ->field('isLike')->equals(false)
                                ->field('date')->range($start_day_week, $end_day_week)
                                ->sort('_id', -1)
                                ->hydrate(false)
                                ->getQuery()
                                ->execute();
                        $post = array();
                        foreach ($q1 as $f) {
                            $post[] = $f;
                        }
                        $totalPosts = count($post);
                        if ($totalPosts > 0) {
                            $percentage+=$this->getContainer()->getParameter('company_ad_posts');
                        }
                    }
                    if ($company->getCompanyType()) {
                        $percentage+=$this->getContainer()->getParameter('company_ad_type');
                    }if ($company->getAvatar()) {
                        $percentage+=$this->getContainer()->getParameter('comapny_ad_avatar');
                    }if ($company->getSize()) {
                        $percentage+=$this->getContainer()->getParameter('company_ad_size');
                    }if ($company->getFoundedin()) {
                        $percentage+=$this->getContainer()->getParameter('company_ad_foundedin');
                    }if ($company->getFounders()) {
                        $percentage+=$this->getContainer()->getParameter('company_ad_founders');
                    }if ($company->getDescription()) {
                        $percentage+=$this->getContainer()->getParameter('company_ad_description');
                    }if ($company->getWebsite()) {
                        $percentage+=$this->getContainer()->getParameter('company_ad_website');
                    }if ($company->getTwitter()) {
                        $percentage+=$this->getContainer()->getParameter('company_ad_twitter');
                    }if ($company->getLinkedIn()) {
                        $percentage+=$this->getContainer()->getParameter('company_ad_linkedin');
                    }if ($company->getInstagram()) {
                        $percentage+=$this->getContainer()->getParameter('company_ad_instagram');
                    }
                } else {
                    if ($company->getUsername()) {
                        $q1 = $dm->createQueryBuilder('DataBaseBundle:Update')
                                ->field('companyUsername')->equals($company->getUsername())
                                ->field('type')->equals(5)
                                ->field('isLike')->equals(false)
                                ->field('date')->range($start_day_week, $end_day_week)
                                ->sort('_id', -1)
                                ->hydrate(false)
                                ->getQuery()
                                ->execute();
                        $post = array();
                        foreach ($q1 as $f) {
                            $post[] = $f;
                        }
                        $totalPosts = count($post);
                        if ($totalPosts > 0) {
                            $percentage+=$this->getContainer()->getParameter('company_posts');
                        }
                    }
                    if ($company->getCompanyType()) {
                        $percentage+=$this->getContainer()->getParameter('company_type');
                    }if ($company->getCompanySubType()) {
                        $percentage+=$this->getContainer()->getParameter('company_subtype');
                    }if ($company->getAvatar()) {
                        $percentage+=$this->getContainer()->getParameter('comapny_avatar');
                    }if ($company->getSize()) {
                        $percentage+=$this->getContainer()->getParameter('company_size');
                    }if ($company->getFoundedin()) {
                        $percentage+=$this->getContainer()->getParameter('company_foundedin');
                    }if ($company->getFounders()) {
                        $percentage+=$this->getContainer()->getParameter('company_founders');
                    }if ($company->getDescription()) {
                        $percentage+=$this->getContainer()->getParameter('company_description');
                    }if ($company->getWebsite()) {
                        $percentage+=$this->getContainer()->getParameter('company_website');
                    }if ($company->getTwitter()) {
                        $percentage+=$this->getContainer()->getParameter('company_twitter');
                    }if ($company->getLinkedIn()) {
                        $percentage+=$this->getContainer()->getParameter('company_linkedin');
                    }if ($company->getInstagram()) {
                        $percentage+=$this->getContainer()->getParameter('company_instagram');
                    }
                }

                $points = 0.6 * $percentage;
                $points = $points + ($totalPosts * 10);
                if ($company->followers) {
                    $points = $points + (2 * count($company->followers));
                }

                //	$points=$points+count($compamy->followers);

                $company->setCompanyPercentage($percentage);
                $company->setCompanyPoints($points);

                $dm->flush();
                $dm->clear();
            }
            $skip++;
        } while ($count < $totalCompanies);
        
        $companies = $dm->createQueryBuilder('DataBaseBundle:Company')
                ->sort('companyPoints', -1)
                ->getQuery()
                ->execute();

        $rank = 1;
        foreach ($companies as $company) {
            $dm->createQueryBuilder('DataBaseBundle:Company')
                    ->update()
                    ->multiple(false)
                    ->field('id')->equals($company->getId())
                    ->field('companyRank')->set($rank)
                    ->getQuery()
                    ->execute();

            $rank++;
        }
        $dm->flush();
        $dm->clear();
        
        
        $companies = $dm->createQueryBuilder('DataBaseBundle:Company')
                ->sort('companyPoints', -1)
                ->getQuery()
                ->execute();

        $rank = 1;
        foreach ($companies as $company) {
            $dm->createQueryBuilder('DataBaseBundle:Company')
                    ->update()
                    ->multiple(false)
                    ->field('id')->equals($company->getId())
                    ->field('companyRank')->set($rank)
                    ->field('oldCompanyRank')->set($company->getCompanyRank())
                    ->getQuery()
                    ->execute();

            $rank++;
        }
        $dm->flush();
        $dm->clear();
        
        
    }

    private function sendEmail($user) {


        switch ($this->getContainer()->getParameter('kernel.environment')) {
            case 'dev':
                $baseLink = 'http://angular.dev';
                break;
            case 'test':
                $baseLink = 'http://angular.dev';
                break;
            case 'adhoc':
                $baseLink = 'https://www-dev.mobintouch.com';
                break;
            case 'prod':
                $baseLink = 'https://www.mobintouch.com';
                break;
            default:
                $baseLink = 'https://www.mobintouch.com';
                break;
        }

        $link = $baseLink . "/edit/profile";

        $message = \Swift_Message::newInstance()
                ->setSubject($user->getName() . ', complete your profile to boost your visibility')
                ->setFrom(array('noreply@mobintouch.com' => 'Mobintouch'))
                ->setTo($user->getEmail())
                ->setContentType("text/html")
                ->setBody(
                $this->getContainer()->get('templating')->render(
                        'APIBundle:Mail:completeprofile.html.twig', array('title' => 'Get visibility!', 'user' => $user, 'link' => $link, 'unsubcribe' => 1)
                )
                )
        ;
        $this->getContainer()->get('mailer')->send($message);
    }

}
