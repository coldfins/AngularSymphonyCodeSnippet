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

class AddOfferSettingsNotificationsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('fix:AddOfferSettingsNotifications')
            ->setDescription('Add Offer Settings Notifications Command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');

        $goodformat = 0;
        $badformat = 0;
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

        //Commented on 20-11-2017
        /*do{

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

                $settings = $user->getSettings();
                if($settings && isset($settings['notifications']) && isset($settings['notifications'][0])){

                    if(!isset($settings['notifications'][0]['email_offersdailyrecap'])) $settings['notifications'][0]['email_offersdailyrecap'] = true;
                    if(!isset($settings['notifications'][0]['email_offeralertduedate'])) $settings['notifications'][0]['email_offeralertduedate'] = true;
                    if(!isset($settings['notifications'][0]['email_offerclosed'])) $settings['notifications'][0]['email_offerclosed'] = true;
                    if(!isset($settings['notifications'][0]['email_replyclosed'])) $settings['notifications'][0]['email_replyclosed'] = true;
                    if(!isset($settings['notifications'][0]['email_offerengagedconversation'])) $settings['notifications'][0]['email_offerengagedconversation'] = true;

                    $goodformat++;

                    $dm->createQueryBuilder('DataBaseBundle:User')
                        // Find the Campaign
                        ->update()
                        ->multiple(false)
                        ->field('_id')->equals($user->getId())


                        ->field('settings')->set($settings)
                        ->field('updateDate')->set(time())


                        // Options
                        ->upsert(false)
                        ->getQuery()
                        ->execute();

                }else{

                    $badformat++;

                    $settings = array(
                        "notifications" =>
                            array( array (
                                'email_intouchrequest' => true,
                                'email_intouchvalidated' => true,
                                'email_touchmail' => true,
                                'email_whovisited' => true,
                                'email_fillprofile' => true,
                                'email_userlike' => true,
                                'email_companyfollow' => true,
                                'email_companylike' => true,

                                'email_offersdailyrecap' => true,
                                'email_offeralertduedate' => true,
                                'email_replyclosed' => true,
                                'email_offerclosed' => true,
                                'email_offerengagedconversation' => true,
                            )),
                        "privacy" =>
                            array( array (
                                'share_data' => true,
                                'contact_information' => false,
                                'cookies' => true,
                            ))
                    );

                    $dm->createQueryBuilder('DataBaseBundle:User')
                        // Find the Campaign
                        ->update()
                        ->multiple(false)
                        ->field('_id')->equals($user->getId())

                        ->field("settings")->set($settings)
                        ->field('updateDate')->set(time())


                        // Options
                        ->upsert(false)
                        ->getQuery()
                        ->execute();
                }

                $dm->flush();
                $dm->clear(); // Detaches all objects from Doctrine!
            }

            $skip++;

        } while($count<$total);*/

        print_r("Good Format : ".$goodformat."\n");
        print_r("Bad Format : ".$badformat."\n");


    }


}

