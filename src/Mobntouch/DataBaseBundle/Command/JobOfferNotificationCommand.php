<?php

namespace Mobntouch\DataBaseBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class JobOfferNotificationCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('notification:joboffers')->setDescription('Weekly job offers notification');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');
        $time = time();
        $count = 0;
        $skip = 0;
        $limit = 10;

        $total = $dm->createQueryBuilder('DataBaseBundle:Jobs')
                ->field('publishStatus')->equals('published')
                ->getQuery()
                ->execute()
                ->count();

        print_r("TOTAL:\n");
        print_r($total);
        print_r("\n");

        $dm->createQueryBuilder('DataBaseBundle:User')
                ->update()
                ->multiple(true)
                ->field('weeklyJobMails')->unsetField()->exists(true)
                ->getQuery()
                ->execute();

        do {

            $jobs = $dm->createQueryBuilder('DataBaseBundle:Jobs')
                    ->field('publishStatus')->equals('published')
                    ->limit($limit)
                    ->skip($limit * $skip)
                    ->getQuery()
                    ->execute();

            foreach ($jobs as $job) {
                print_r("\n");
                $count++;

                $dm->createQueryBuilder('DataBaseBundle:User')
                        ->update()
                        ->multiple(true)
                        ->field('jobFilters')->elemMatch(array(
                            'search' => array('$in' => $job->getSearch()),
                            'alerts' => true
                        ))
                        ->field('weeklyJobMails')->push(array('$each' => array(array(
                                    'companyId' => $job->getCompany()['id'],
                                    'jobId' => $job->getId(),
                                    'username' => $job->getCompany()['username'],
                                    'name' => $job->getCompany()['name'],
                                    'avatar' => $job->getCompany()['avatar'],
                                    'city' => $job->getLocation()['city'],
                                    'country' => $job->getLocation()['country'],
                                    'basedCountry' => $job->getLocation()['basedCountry'],
                                    'description' => $job->getCompany()['description'],
                                    'jobSlug' => $job->getSlug(),
                                    'jobTitle' => $job->getJobTitle(),
                                    'jobLocation' => $job->getLocation(),
                                    'jobSearch' => $job->getSearch(),
                                    'date' => time() * 1000
                                )), '$slice' => -90))
                        ->field('updateDate')->set($time)
                        ->getQuery()
                        ->execute();



                /*
                  ->field('jobFilters.search')->in($job->getSearch())
                  ->field('companyPage.employee.company')->notEqual($job->getCompany()['username'])
                  ->field('companyPage.recruiter.company')->notEqual($job->getCompany()['username'])
                  ->field('alerts')->push(array('$each' => array(array(

                  )), '$slice' => -90))
                  ->field('alertsNotifications')->inc(1)
                  ->field('alerts')->push(array('$each' => array(array(
                  'id' => $job->getCompany()['id'],
                  'type' => 14, //New job offer notification
                  'read' => false,
                  'action' => 'has posted a job offer that match your interests',
                  'username' => $job->getCompany()['username'],
                  'name' => $job->getCompany()['name'],
                  'lastname' => '',
                  'avatar' => $job->getCompany()['avatar'],
                  'slug' => $job->getSlug(),
                  'date' => time() * 1000
                  )), '$slice' => -90))
                  ->field('updateDate')->set(time())
                  ->getQuery()
                  ->execute(); */

                /* $users = $dm->createQueryBuilder('DataBaseBundle:User')
                  ->update()
                  ->multiple(true)
                  ->field('jobFilters.search')->in($job->getSearch())
                  ->field('companyPage.employee.company')->notEqual($job->getCompany()['username'])
                  ->field('companyPage.recruiter.company')->notEqual($job->getCompany()['username'])
                  ->getQuery()
                  ->execute(); */


                //print_r($job->getJobTitle());
            }

            $skip++;
        } while ($count < $total);

        $dm->flush();
        $dm->clear();

        $allUsers = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('weeklyJobMails')->exists(true)
                ->getQuery()
                ->execute();


        foreach ($allUsers as $user) {

            $mailForJob = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'name' => $user->getName(),
                'lastname' => $user->getLastname(),
                'email' => $user->getEmail(),
                'jobTitle' => $user->getJobTitle(),
                'avatar' => $user->getAvatar(),
                'cover' => $user->getCover(),
                'skills' => $user->getCompetences(),
                'keywords' => $user->getKeywords(),
                'jobs' => array()
            ];

            $jobFilters = array_filter($user->getJobFilters(), function($filter) {
                return $filter['alerts'] == true;
            });

            foreach ($jobFilters as $jFilter) {
                $weekly = $user->getWeeklyJobMails() ? $user->getWeeklyJobMails() : array();
                foreach ($weekly as $w) {
                    $w['search'] = $jFilter['search'];
                    $common = array_uintersect($w['jobSearch'], $jFilter['search'], 'strcasecmp');
                    if (count($common) > 0) {
                        $w['common'] = $common;
                        $w['filter'] = $jFilter['name'];
                        $mailForJob['jobs'][$w['jobId']][] = $w;
                    }
                    // print_r($mailForJob[0]);
                    //die;
                    //print_r(array_intersect($w['jobSearch'], $jFilter['search']));
                    //die;
                    //print_r($jFilter['search']);
                    //die;
                    //print_r(array_intersect($w['jobSearch'], $jFilter['search']));

                    /* $count = count(array_intersect($w['jobSearch'], $jFilter['search']));
                      if ($count > 0) {
                      $w['filter'] = $jFilter['name'];
                      $w['filterCount'] = $count;
                      $mailForJob[] = $w;
                      } */
                }
            }

            //print_r($mJob);
            //print_r($mailForJob);

            foreach ($mailForJob['jobs'] as $key => $mJob) {
                usort($mJob, function ($a, $b) {
                    return count($b['common']) - count($a['common']);
                });

                $mailForJob['jobs'][$key] = $mJob[0];

                //print_r($mJob[0]);
                //die;
            }
            print_r($mailForJob);

            //Email Sending code goes here
        }


        $u = $dm->getRepository('DataBaseBundle:User')->findOneBy(array('email' => 'vedtest2@gmail.com'));
        print_r($this->sendEmail($u));


        die;

        /* $dm->createQueryBuilder('DataBaseBundle:User')
          ->update()
          ->multiple(true)
          ->field('weeklyJobMails')->unsetField()->exists(true)
          ->getQuery()
          ->execute(); */
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
                ->setTo('jatinlalit700@gmail.com')
                ->setContentType("text/html")
                ->setBody(
                $this->getContainer()->get('templating')->render(
                        'APIBundle:Mail:completeprofile.html.twig', array('title' => 'Get visibility!', 'user' => $user, 'link' => $link, 'unsubcribe' => 1)
                )
        );
        return $this->getContainer()->get('mailer')->send($message);
    }

}
