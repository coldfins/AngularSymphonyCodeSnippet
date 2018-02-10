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

class ValidateMailCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('validate:mail')
            ->setDescription('Validate emails')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');

        $count = 0;
        $skip = 0;
        $limit = 10;

        $total = $dm->createQueryBuilder('DataBaseBundle:User')
            ->field('validated')->equals(false)
            ->field('token')->exists(true)
            ->getQuery()
            ->execute()->count();

        print_r("TOTAL:\n");
        print_r($total);
        print_r("\n");

        //Commented on 20-11-2017
        /*do{

            $users = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('validated')->equals(false)
                ->field('token')->exists(true)
                ->limit($limit)
                ->skip($limit*$skip)
                ->getQuery()
                ->execute();

            foreach($users as $user){
                print_r($user->getUsername());
                print_r("\n");
                $count++;
                $this->sendEmail($user);
            }

            $skip++;

        } while($count<$total);*/


    }

    private function sendEmail($user){

        switch($this->getContainer()->getParameter('kernel.environment')){
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

        $email = $user->getEmail();
        $username = $user->getUsername();
        $token = $user->getToken();
        $hash = $user->getEmailValidationHash();
        $link = $baseLink."/automatic/email/validation/$hash/$email/$username/$token";


        $message = \Swift_Message::newInstance()
            ->setSubject($user->getName().', please confirm your email address')
            ->setFrom(array('noreply@mobintouch.com'=> 'Mobintouch'))
            ->setTo($user->getEmail())
            ->setContentType("text/html")
            ->setBody(
                $this->getContainer()->get('templating')->render(
                    'APIBundle:Mail:emailValidation.html.twig',
                    array('title' => 'Welcome to Mobintouch!', 'user' => $user, 'link' => $link, 'unsubcribe' => 0)
                )
            )
        ;
        $this->getContainer()->get('mailer')->send($message);
    }


}

