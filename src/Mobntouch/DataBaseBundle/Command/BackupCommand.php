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

use Mobntouch\DataBaseBundle\Document\UsersBackup;
use Mobntouch\DataBaseBundle\Document\User;


class BackupCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('fix:Backup')
            ->setDescription('Backup Command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');

        $count = 0;
        $remaining = 0;
        $skip = 0;
        $limit = 10;

        $remainingArray = array();

        $total = $dm->createQueryBuilder('DataBaseBundle:User')
            ->field('username')->exists(false)
            ->getQuery()
            ->execute()->count();

        print_r("TOTAL:\n");
        print_r($total);
        print_r("\n");

        do{

            $users = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('username')->exists(false)
                //->field('username')->equals('holahola')
                ->limit($limit)
                ->skip($limit*$skip)
                ->getQuery()
                ->execute();

            foreach($users as $user){
                print_r("ID: ".$user->getId()."\n");
                $count++;
                print_r("count : ".$count."\n");

                $backup = $dm->getRepository('DataBaseBundle:UsersBackup')->findOneBy( array('id'=> $user->getId()));
                //$backup = $dm->getRepository('DataBaseBundle:User')->findOneBy( array('id'=> $user->getId()));

                if($backup){
                    print_r("Username: ".$backup->getUsername()." ==> ".$backup->getEmail()."\n");

                    //$dm->persist($user);
                    //$dm->flush();
                    //print_r($backup);
                    //print_r(get_object_vars($backup));

                    $arrayBackup = get_object_vars($backup);


                    $q = $dm->createQueryBuilder('DataBaseBundle:User')
                        // Find the Campaign
                        ->update()
                        ->multiple(true)
                        ->field('id')->equals($backup->getId());

                    foreach($arrayBackup as $key=>$value){
                        $update = true;
                        switch($key){
                            case 'alerts':
                            case 'whoVisitedMe':
                            case 'iVisited':
                            case 'buyTraffic':
                            case 'sellTraffic':
                            case 'experiences':
                            case 'iosApps':
                            case 'androidApps':
                            case 'categories':
                            case 'languages':
                            case 'competences':
                            case 'followers':
                            case 'following':
                            case 'paymentTerms':
                            case 'paymentMethods':
                            //case 'trackingServices':
                            case 'inTouch':
                            case 'companySubType':
                            //case 'companyPage':
                            case 'references':
                            case 'events':
                            //case 'settings':
                            case 'linkedInInvites':
                            case 'linkedInCompanies':
                            case 'linkedInCompaniesID':
                                // ARRAYS
                                if($value==null) $value = array();
                                break;
                            case 'alertsNotifications':
                            case 'totalReceivedEmails':
                            case 'totalSentEmails':
                            case 'emailsNotifications':
                            case 'inTouchCounter':
                                // INT
                            if($value==null) $value = 0;
                                break;
                            case 'responseRate':
                                // FLOAT
                            if($value==null) $value = floatval(0.0);
                                break;
                            case 'validated':
                            case 'privacyHidden':
                                // BOOL
                            if($value==null) $value = false;
                                break;
                            case 'trackingServices':
                            case 'companyPage':
                            case 'settings':
                                // NO UPDATE IF NULL
                            if($value==null) $update = false;
                            break;
                                break;
                            default:
                                break;
                        }
                        if($update) $q->field($key)->set($value);
                    }

                    // Options
                    $q->field('updateDate')->set(time())
                        ->upsert(false)
                        ->getQuery()
                        ->execute();

                    $dm->flush();
                    $dm->clear(); // Detaches all objects from Doctrine!

                    
                }else{
                    print_r("Remaining...\n");
                    $search = $dm->getRepository('DataBaseBundle:UserSearch')->findOneBy( array('userID'=> $user->getId()));

                    $remainingArray[] = $search;

                    $remaining++;

                }
                print_r("------\n");

            }

            $skip++;



        } while($count<$total);

        print_r("REMAINING : $remaining\n");
        print_r($remainingArray);
    }


}

