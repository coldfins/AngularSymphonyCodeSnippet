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

class OffersDailyRecapCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('offers:daily_recap')
            ->setDescription('Offers Daily Recap Command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set("memory_limit", "-1");
        set_time_limit(0);

        // Set up database //commented on 20-11-2017
        /*$dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');

        $users = $dm->createQueryBuilder('DataBaseBundle:User')
            ->field('validated')->equals(true)
            ->field('settings.notifications.0.email_offersdailyrecap')->equals(true)
            ->getQuery()
            ->execute();

        foreach($users as $user){


            $offers = $dm->createQueryBuilder('DataBaseBundle:Offer')
                //->field('status')->equals('LIVE')
                ->field('userID')->equals($user->getId())
                ->getQuery()
                ->execute();

            if($offers) {

                $active = 0;
                $yesterday = date('m-d-y',strtotime("-1 days"));
                //$yesterday = date('m-d-y');

                foreach($offers as $offer){
                    $history = $offer->getHistory();
                    if(isset($history[$yesterday]) and isset($history[$yesterday]['time'])) {
                        $active++;
                    }

                }

                if($active>0){

                    print_r("\nUSER:\n");
                    print_r($user->getUsername());
                    print_r("\n# Offers: ");
                    print_r($active);
                    print_r("\n");

                    $this->sendDailyRecapEmail($offers,$user, $active);

                }
            }
        }*/
    }

    private function sendDailyRecapEmail($offers, $user, $active){

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

        $yesterday =  date('M d, Y',strtotime("-1 days"));
        $title = "Daily status of your active offer ".$yesterday;
        if($active>1) $title = "Daily status of your $active active offers ".$yesterday;

        $message = \Swift_Message::newInstance()
            ->setSubject("Offer : Daily Report For ".$yesterday)// (Jun 08, 2015))
            ->setFrom(array('noreply@mobintouch.com'=> 'Mobintouch Offers'))
            ->setTo($user->getEmail())
            ->setContentType("text/html")
            ->setBody(
                $this->getContainer()->get('templating')->render(
                    'APIBundle:Mail:offerDailyRecap.html.twig',
                    array('title' => $title, 'user' => $user, 'offers' => $offers, 'baseLink' => $baseLink, 'unsubcribe' => 1, 'active' => $active)
                )
            )
        ;
        $this->getContainer()->get('mailer')->send($message);
    }

}

