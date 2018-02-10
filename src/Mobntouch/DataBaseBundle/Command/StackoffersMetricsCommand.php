<?php

/**
 * User: ved
 * Date: 17/03/1
 * Time: 11:32
 */

namespace Mobntouch\DataBaseBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class StackoffersMetricsCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('sendMail:StackoffersMetrics')
                ->setDescription('Send Mail Of Stack Offer Matrics')
        ;
    }

   protected function execute(InputInterface $input, OutputInterface $output) {


        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');

        //print_r($dm);

        $currentTime = time();
        $last7d = strtotime("-7 day");

        $numberOfLiveOffers = $dm->createQueryBuilder('DataBaseBundle:Offer')
                        ->field('status')->equals('LIVE')
                        ->getQuery()
                        ->execute()->count();
       
        $numberOfClosedOffers = $dm->createQueryBuilder('DataBaseBundle:Offer')
                        ->field('status')->equals('CLOSED')
                        ->getQuery()
                        ->execute()->count();

        $numberOfReplies = $dm->createQueryBuilder('DataBaseBundle:OfferReply')
                        ->getQuery()
                        ->execute()->count();


        /*$message = \Swift_Message::newInstance()
                ->setSubject("Stack Offers Metrics")
                ->setFrom(array('noreply@mobintouch.com' => 'Mobintouch Stack Offers Metrics'))
                ->setTo('vedtest2@gmail.com')
                ->setContentType("text/html")
                ->setBody('<div><strong>Number Of Live Offers : </strong>' . $numberOfLiveOffers . '</div><div><strong>Number Of Closed Offers : </strong>' . $numberOfClosedOffers . '</div><div><strong>Number Of Replies : </strong>' . $numberOfReplies . '</div>');
        $this->getContainer()->get('mailer')->send($message);*/

        print_r("Live: " . $numberOfLiveOffers . "\n");
        print_r("Closed: " . $numberOfClosedOffers . "\n");
        print_r("Replies: " . $numberOfReplies . "\n");
    }

}
