<?php

namespace Mobntouch\DataBaseBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Mobntouch\APIBundle\Classes\Utility;
use Mobntouch\DataBaseBundle\Document\User;

class NewApplicantsCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('newapplicants:list')->setDescription('New applicants list');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');
        $count = 0;
        $skip = 0;
        $limit = 10;

        $total = $dm->createQueryBuilder('DataBaseBundle:Jobs')
                ->field('publishStatus')->equals('published')
                ->getQuery()
                ->execute()
                ->count();

        do {
            $jobs = $dm->createQueryBuilder('DataBaseBundle:Jobs')
                    ->field('publishStatus')->equals('published')
                    ->limit($limit)
                    ->skip($limit * $skip)
                    ->getQuery()
                    ->execute();

            foreach ($jobs as $job) {
                $count++;
                $weeekApplicants = $this->getListApplicants($job->getAppliedBy());
                if (count($weeekApplicants) > 0) {
                    echo "send email";
                    $this->sendEmail($dm, $job, $weeekApplicants);
                } else
                    echo "email NOT sent";
            }
            $skip++;
        } while ($count < $total);
    }

    private function getListApplicants($appliedBy) {
        $weeekApplicants = array();
        $today = strtotime("00:00:00 UTC"); // UTC
        $lastWeek = strtotime("-1 week", $today);

        foreach ($appliedBy as $applicant) {
            if ($applicant['date'] >= $lastWeek) {
                $weeekApplicants[] = $applicant;
            }
        }

        // Sort multi-dimensional array by length
        usort($weeekApplicants, function($a, $b) {
            return $b['date'] >= $a['date'];
        });

        //echo count($weeekApplicants);
        return $weeekApplicants;
    }

    private function sendEmail($dm, $job, $weekApplicants) {
        $au = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('id')->equals($job->getCreatedBy()['id'])
                ->field('validated')->equals(true)
                ->field('settings.notifications.0.email_qa')->equals(true)
                ->getQuery()
                ->getSingleResult();

        if (!$au instanceof User) {
            return;
        }

        switch ($this->getContainer()->getParameter('kernel.environment')) {
            case 'dev':
                $mainUrl = 'http://angular.dev';
                $baseURL = 'http://angular.dev';
                $manageUrl = 'http://angular.dev/recruits/manage';
                $manageNotification = 'http://angular.dev/settings/notifications';
                break;
            case 'adhoc':
                $mainUrl = 'https://www-dev.mobintouch.com';
                $baseURL = 'https://cdn-dev.mobintouch.com';
                $manageUrl = 'https://www-dev.mobintouch.com/recruits/manage';
                $manageNotification = 'https://www-dev.mobintouch.com/settings/notifications';
                break;
            case 'prod':
                $mainUrl = 'https://www.mobintouch.com';
                $baseURL = 'https://cdn.mobintouch.com';
                $manageUrl = 'https://www.mobintouch.com/recruits/manage';
                $manageNotification = 'https://www.mobintouch.com/settings/notifications';
                break;
            default:
                $mainUrl = 'https://www.mobintouch.com';
                $baseURL = 'https://cdn.mobintouch.com';
                $manageUrl = 'https://www.mobintouch.com/recruits/manages';
                $manageNotification = 'https://www.mobintouch.com/settings/notifications';
                break;
        }

        $list = '';
        foreach ($weekApplicants as $a) {
            $u = $dm->getRepository('DataBaseBundle:User')->find($a['id']);
            if (!$u instanceof User) {
                continue;
            }
            $applicantProfileLink = $mainUrl . '/profile/' . $a['username'];
            $applicantPictureLink = isset($a['avatar']) && $a['avatar'] && !empty($a['avatar']) ? $baseURL . $a['avatar'] : $baseURL . '/img/mit-default-avatar.png';
            $miniResume = substr(strip_tags($u->getMiniResume() ? $u->getMiniResume() : $u->getSummary()), 0, 200);
            $list .= '<tr><td align="left" style="width:100px;vertical-align:top;"><span><a href="' . $applicantProfileLink . '"><img alt="' . $a['name'] . ' ' . $a['lastname'] . '" src="' . $applicantPictureLink . '" style="margin-top:5px;width: 90px; height: 90px; border-radius: 900px;" width="90" height="90"/></a></span></td><td align="left" style="font-family: \"Roboto\", Helvetica, Arial, sans-serif; font-size:16px; line-height:20px; font-weight:400; color:#000"><p align="left" style="color:#7e8890;font-size:15px; margin-top:0px;"><a href="' . $applicantProfileLink . '" style="text-decoration:none;border-style:none;color:#1181d2;font-weight:700;">' . $a['name'] . ' ' . $a['lastname'] . '</a> · ' . $a['jobTitle'] . ' · ' . $a['city'] . '</p><p align="left" style="font-size:15px !important; margin-top:0px;">' . $miniResume . ' ...</p><p align="left" style="color:#7e8890;font-size:15px; margin-top:0px;">Interested in ' . $job->getCompany()['name'] . ' · <a href="' . $applicantProfileLink . '" style="text-decoration:none;border-style:none;color:#1181d2;font-weight:700;">See full profile »</a></p></td></tr><tr height="20"><td></td></tr>';
        }
        $list = trim(preg_replace('/\s+/', ' ', $list));

        /* $message = \Swift_Message::newInstance()
          ->setSubject($user->getName() . ', people visited your Mobintouch profile')
          ->setFrom(array('noreply@mobintouch.com' => 'Mobintouch'))
          ->setTo($user->getEmail())
          ->setContentType("text/html")
          ->setBody(
          $this->getContainer()->get('templating')->render(
          'APIBundle:Mail:whovisited.html.twig', array('title' => 'See who visited your profile', 'user' => $user, 'link' => $link, 'baseLink' => $baseLink, 'baseURL' => $baseURL, 'list' => $weeekVisits, 'unsubcribe' => 1)
          )
          )
          ;
          $this->getContainer()->get('mailer')->send($message); */

        $applicants = count($weekApplicants);
        $applicant_or_applicants = $applicants > 1 ? 'applicant' : 'applicants';

        $params = array(
            'personalizations' => array(
                array(
                    'to' => array(
                        array('email' => $au->getEmail())
                    ),
                    'substitutions' => array(
                        ':logo_link' => $mainUrl,
                        ':logo_name_link' => $mainUrl,
                        ':number_applicants' => $applicants . "",
                        ':manage_jobs_link' => $manageUrl,
                        ':settings_notifications_link' => $manageNotification,
                        ':job_title' => $job->getJobTitle(),
                        ':applicants_link' => $manageUrl,
                        ':loop' => $list
                    )
                ),
            ),
            'from' => array(
                'email' => "noreply@mobintouch.com",
                'name' => "Mobintouch"
            ),
            'subject' => count($applicants) . ' new people interested in ' . $job->getCompany()['name'],
            'content' => array(array(
                    'type' => 'text/html',
                    'value' => ' '
                )
            ),
            'template_id' => $this->getContainer()->getParameter('template_new_applicants_list_id'),
            'asm' => array(
                'group_id' => $this->getContainer()->getParameter('group_recruit')
            )
        );
        Utility::sendgrid_mail(json_encode($params));
    }

}
