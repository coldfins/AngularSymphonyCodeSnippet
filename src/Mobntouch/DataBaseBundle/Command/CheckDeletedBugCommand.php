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

class CheckDeletedBugCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('check:deletedBug')
            ->setDescription('Check Deleted Bug Command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');

        $deleted = $dm->createQueryBuilder('DataBaseBundle:User')
            ->field('username')->exists(false)
            ->getQuery()
            ->execute()->count();

        echo "Accounts deleted = ".$deleted."\n";

        $companyPages = $dm->createQueryBuilder('DataBaseBundle:User')
            ->field('companyPage.administrator')->exists(true)
            ->getQuery()
            ->execute()->count();

        echo "Company Page = ".$companyPages."\n";

        $trackingServices = $dm->createQueryBuilder('DataBaseBundle:User')
            ->field('trackingServices.0')->exists(true)
            ->getQuery()
            ->execute()->count();

        echo "Tracking Services = ".$trackingServices."\n";

        //if(($deleted+$companyPages+$trackingServices)>0) $this->sendEmail($deleted,$companyPages,$trackingServices);

    }


    private function sendEmail($deleted,$companyPages,$trackingServices){
        echo "Sending email...\n";

        $message = \Swift_Message::newInstance()
            ->setSubject('ALERT: DATABASE!')
            ->setFrom(array('noreply@mobintouch.com'=> 'Mobintouch'))
            ->setTo(array('josep@mediaswapp.com', 'georges@mediaswapp.com'))
            ->setContentType("text/html")
            ->setBody(
                $this->getContainer()->get('templating')->render(
                    'APIBundle:Mail:checkDeletedAccounts.html.twig',
                    array('deleted' => $deleted,'companyPages' => $companyPages,'trackingServices' => $trackingServices)
                )
            )
        ;
        $this->getContainer()->get('mailer')->send($message);
    }


}

