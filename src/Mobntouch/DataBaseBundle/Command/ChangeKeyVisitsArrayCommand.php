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

class ChangeKeyVisitsArrayCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('fix:ChangeKeyVisitsArrayCommand')
            ->setDescription('Change Key Visits Array Command')
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
            ->field('validated')->equals(true)
            ->getQuery()
            ->execute()->count();

        print_r("TOTAL:\n");
        print_r($total);
        print_r("\n");

        do{

            $users = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('validated')->equals(true)
                ->limit($limit)
                ->skip($limit*$skip)
                ->getQuery()
                ->execute();

            foreach($users as $user){
                print_r($user->getUsername());
                print_r("\n");
                $count++;
                print_r("count : ".$count."\n");

                $whovisited = $user->getWhoVisitedMe();
                $nbwho = count($whovisited);

                $ivisited = $user->getIVisited();
                $nbvisited = count($ivisited);

                $q = $dm->createQueryBuilder('DataBaseBundle:User')
                    // Find the Campaign
                    ->update()
                    ->multiple(false)
                    ->field('_id')->equals($user->getId());

                if($nbwho>0){
                    $temp1 = array();
                    foreach($whovisited as $who){
                        $temp1[] = $who;
                    }
                    //$user->setWhoVisitedMe($temp1);
                    $q->field('whoVisitedMe')->set($temp1);

                }
                if($nbvisited>0){
                    $temp2 = array();
                    foreach($ivisited as $visit){
                        $temp2[] = $visit;
                    }
                    //$user->setIVisited($temp2);
                    $q->field('iVisited')->set($temp2);

                }

                if($nbvisited>0 || $nbvisited>0){

                    // Options
                    $q->field('updateDate')->set(time())
                        ->upsert(false)
                        ->getQuery()
                        ->execute();

                }

                $dm->flush();
                $dm->clear(); // Detaches all objects from Doctrine!

            }

            $skip++;

        } while($count<$total);


    }


}

