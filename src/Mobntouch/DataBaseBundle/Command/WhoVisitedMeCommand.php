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
use Mobntouch\APIBundle\Classes\Utility;
use Mobntouch\DataBaseBundle\Document\User;

class WhoVisitedMeCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('whovisited:me')->setDescription('Who Visited Me');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');
        $count = 0;
        $skip = 0;
        $limit = 10;

        $total = $dm->createQueryBuilder('DataBaseBundle:User')
                        ->field('validated')->equals(true)
                        ->field('settings.notifications.0.email_whovisited')->equals(true)
                        ->field('whoVisitedMe')->exists(true)
                        ->getQuery()
                        ->execute()->count();

        do {
            $users = $dm->createQueryBuilder('DataBaseBundle:User')
                    ->field('validated')->equals(true)
                    ->field('settings.notifications.0.email_whovisited')->equals(true)
                    ->field('whoVisitedMe')->exists(true)
                    ->limit($limit)
                    ->skip($limit * $skip)
                    ->getQuery()
                    ->execute();

            foreach ($users as $user) {
                print_r($user->getUsername());
                print_r("\n");
                $count++;
                $weeekVisits = $this->getListWhoVisited($user->getWhoVisitedMe());
                if (count($weeekVisits) > 0) {
                    echo "send email";
                    $this->sendEmail($dm, $user, $weeekVisits);
                } else
                    echo "email NOT sent";
            }
            $skip++;
        } while ($count < $total);
    }

    private function getListWhoVisited($whoVisitedMe) {
        $weeekVisits = array();
        $today = strtotime("00:00:00 UTC"); // UTC
        $lastWeek = strtotime("-1 week", $today);

        $users = array(); // Removed duplicate profiles
        foreach ($whoVisitedMe as $visit) {
            if ($visit['date'] >= $lastWeek && !in_array($visit['username'], $users)) {
                $weeekVisits[] = $visit;
                $users[] = $visit['username'];
            }
        }

        // Sort multi-dimensional array by length
        usort($weeekVisits, function($a, $b) {
            return $b['date'] >= $a['date'];
        });

        echo count($weeekVisits);
        return $weeekVisits;
    }

    private function sendEmail($dm, $user, $weekVisits) {
        switch ($this->getContainer()->getParameter('kernel.environment')) {
            case 'dev':
                $mainUrl = 'http://angular.dev';
                $baseURL = 'http://angular.dev';
                $baseLink = 'http://angular.dev/profile';
                $link = "http://angular.dev/profile-visitors";
                break;
            case 'adhoc':
                $mainUrl = 'https://www-dev.mobintouch.com';
                $baseURL = 'https://cdn-dev.mobintouch.com';
                $baseLink = 'https://www-dev.mobintouch.com/profile';
                $link = "https://www-dev.mobintouch.com/profile-visitors";
                break;
            case 'prod':
                $mainUrl = 'https://www.mobintouch.com';
                $baseURL = 'https://cdn.mobintouch.com';
                $baseLink = 'https://www.mobintouch.com/profile';
                $link = "https://www.mobintouch.com/profile-visitors";
                break;
            default:
                $mainUrl = 'https://www.mobintouch.com';
                $baseURL = 'https://cdn.mobintouch.com';
                $baseLink = 'https://www.mobintouch.com/profile';
                $link = "https://www.mobintouch.com/profile-visitors";
                break;
        }

        $list = '';
        foreach ($weekVisits as $w) {
            $u = $dm->getRepository('DataBaseBundle:User')->find($w['id']);
            if (!$u instanceof User) {
                continue;
            }
            $visitorProfileLink = $mainUrl . '/profile/' . $w['username'];
            $visitorPictureLink = isset($w['avatar']) && $w['avatar'] && !empty($w['avatar']) ? $baseURL . $w['avatar'] : $baseURL . '/img/mit-default-avatar.png';
            $jobtitlecompany = isset($w['jobTitle']) && isset($w['company']) ? $w['jobTitle'] . ' at ' . $w['company'] : $w['jobTitle'];
            $list .= '<tr>
                        <td align="left" style="width:100px">
                            <span><a href="' . $visitorProfileLink . '"><img alt="' . $w['name'] . ' ' . $w['lastname'] . '" src="' . $visitorPictureLink . '" style="margin: 0px 0px; width: 90px; height: 90px;border-radius:900px;" width="90" height="90"/></a></span>
                        </td>
                        <td align="left" style="font-family: \"Roboto\", Helvetica, Arial, sans-serif; font-size:16px; line-height:20px; font-weight:400; color:#7e8890">
                            <p align="left" style="font-size:15px !important; margin-top:0px;"><a style="text-decoration:none;border-style:none;color:#1181d2;font-weight:700;" href=":visitor_profile_link">' . $w['name'] . ' ' . $w['lastname'] . '</a> ·  ' . $u->getCity() . '</p>
                            <p align="left" style="font-size:15px !important; margin-top:0px;">' . $jobtitlecompany . '</p>
                            <a href="' . $visitorProfileLink . '" style="text-decoration:none;border-style:none;font-family: "Roboto", Helvetica, Arial, sans-serif;-webkit-font-smoothing:antialiased;color:#1181d2;font-size:15px;font-weight:700;">View profile »</a>
                        </td>
                    </tr>
                    <tr height="20">
                        <td></td>
                    </tr>';
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

        $visitors = count($weekVisits);
        $person_or_persons = $visitors > 1 ? 'persons' : 'person';

        $params = array(
            'personalizations' => array(
                array(
                    'to' => array(
                        array('email' => $user->getEmail())
                    ),
                    'substitutions' => array(
                        ':logo_link' => $mainUrl,
                        ':logo_name_link' => $mainUrl,
                        ':see_who_visited_your_profile_link' => $link,
                        ':loop' => $list
                    )
                ),
            ),
            'from' => array(
                'email' => "noreply@mobintouch.com",
                'name' => "Mobintouch"
            ),
            'subject' => $visitors . ' ' . $person_or_persons . ' visited your profile',
            'content' => array(array(
                    'type' => 'text/html',
                    'value' => ' '
                )
            ),
            'template_id' => $this->getContainer()->getParameter('template_whovisted_profile_id'),
            'asm' => array(
                'group_id' => $this->getContainer()->getParameter('group_whovisted_profile')
            )
        );
        Utility::sendgrid_mail(json_encode($params));
    }

}
