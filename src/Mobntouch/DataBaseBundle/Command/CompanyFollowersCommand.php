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
use Mobntouch\DataBaseBundle\Document\Company;

class CompanyFollowersCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('company:followers')->setDescription('Company new followers');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');
        $count = 0;
        $skip = 0;
        $limit = 10;

        $total = $dm->createQueryBuilder('DataBaseBundle:Company')
                ->getQuery()
                ->execute()
                ->count();

        do {
            $companies = $dm->createQueryBuilder('DataBaseBundle:Company')
                    ->limit($limit)
                    ->skip($limit * $skip)
                    ->getQuery()
                    ->execute();

            foreach ($companies as $company) {
                print_r($company->getUsername());
                print_r("\n");
                $count++;
                $weeekFollowers = $this->getListFollowers($company->getFollowers());
                if (count($weeekFollowers) > 0) {
                    echo "send email";
                    $this->sendEmail($dm, $company, $weeekFollowers);
                } else
                    echo "email NOT sent";
            }
            $skip++;
        } while ($count < $total);
    }

    private function getListFollowers($followers) {
        $weeekFollowers = array();
        $today = strtotime("00:00:00 UTC"); // UTC
        $lastWeek = strtotime("-1 week", $today);

        $newFollowers = array(); // Removed duplicate profiles
        foreach ($followers as $follow) {
            if ($follow['date'] >= $lastWeek && !in_array($follow['username'], $newFollowers)) {
                $weeekFollowers[] = $follow;
                $newFollowers[] = $follow['username'];
            }
        }

        // Sort multi-dimensional array by length
        usort($weeekFollowers, function($a, $b) {
            return $b['date'] >= $a['date'];
        });

        echo count($weeekFollowers);
        return $weeekFollowers;
    }

    private function sendEmail($dm, $company, $weekFollowers) {
        $weekFollowers = array_slice($weekFollowers, 0, 3); //First three follow in list
        $adminEmails = array();
        $admins = $company->getAdministrators();
        foreach ($admins as $admin) {
            $tempAdmin = $dm->createQueryBuilder('DataBaseBundle:User')
                    ->field('_id')->equals($admin['id'])
                    ->getQuery()
                    ->getSingleResult();

            if ($tempAdmin) {
                $settings = $tempAdmin->getSettings();
                if (isset($settings['notifications']) and isset($settings['notifications'][0]) and isset($settings['notifications'][0]['email_companyfollow']) and $settings['notifications'][0]['email_companyfollow']) {
                    $adminEmails[] = array('email' => $tempAdmin->getEmail());
                }
            }
        }

        if (count($adminEmails) <= 0) {
            return;
        }

        $totalFolowers = count($weekFollowers);
        $firstFollower = $weekFollowers[0];
        $subject = $firstFollower['name'] . ' ' . $firstFollower['lastname'] . ' is now following ' . $company->getName();
        if ($totalFolowers > 1) {
            $subject .= ', and ' . ($totalFolowers - 1) . ' more';
        }
        $follower_or_followers = $totalFolowers > 1 ? 'followers' : 'follower';
        switch ($this->getContainer()->getParameter('kernel.environment')) {
            case 'adhoc':
                $mainUrl = 'https://www-dev.mobintouch.com';
                $baseURL = 'https://cdn-dev.mobintouch.com';
                break;
            case 'prod':
                $mainUrl = 'https://www.mobintouch.com';
                $baseURL = 'https://cdn.mobintouch.com';
                break;
            default:
                $mainUrl = 'https://www.mobintouch.com';
                $baseURL = 'https://cdn.mobintouch.com';
                break;
        }

        $list = '';
        foreach ($weekFollowers as $wf) {
            $followerProfileLink = $mainUrl . '/profile/' . $wf['username'];
            $followerPictureLink = isset($wf['avatar']) && $wf['avatar'] && !empty($wf['avatar']) ? $baseURL . $wf['avatar'] : $baseURL . '/img/mit-default-avatar.png';
            $jobTitleCompany = isset($wf['jobTitle']) && isset($wf['company']) ? $wf['jobTitle'] . ' at ' . $wf['company'] : $wf['jobTitle'];
            $list .= '<tr>
                            <td align="left" style="width:100px">
                                <span><a href="' . $followerProfileLink . '"><img alt="' . $wf['name'] . ' ' . $wf['lastname'] . '" src="' . $followerPictureLink . '" style="margin: 0px 0px; width: 90px; height: 90px;border-radius:900px;" width="90" height="90"/></a></span>
                            </td>
                            <td align="left" style="font-family: \'Roboto\', Helvetica, Arial, sans-serif; font-size:16px; line-height:20px; font-weight:400; color:#7e8890">
                                <p align="left" style="font-size:15px !important; margin-top:0px;"><a style="text-decoration:none;border-style:none;color:#1181d2;font-weight:700;" href="' . $followerProfileLink . '">' . $wf['name'] . ' ' . $wf['lastname'] . '</a></p>
                                <p align="left" style="font-size:15px !important; margin-top:0px;">' . $jobTitleCompany . '</p>
                            </td>
                        </tr>
                        <tr height="20">
                            <td></td>
                        </tr>';
        }
        $list = trim(preg_replace('/\s+/', ' ', $list));

        $params = array(
            'personalizations' => array(
                array(
                    'to' => $adminEmails,
                    'substitutions' => array(
                        ':logo_link' => $mainUrl,
                        ':logo_name_link' => $mainUrl,
                        ':follower_or_followers' => $follower_or_followers,
                        ':followers_box_link' => $mainUrl . '/mycompany',
                        ':number_total_followers' => "$totalFolowers",
                        ':loop' => $list
                    )
                ),
            ),
            'from' => array(
                'email' => "noreply@mobintouch.com",
                'name' => "Mobintouch"
            ),
            'subject' => $subject,
            'content' => array(array(
                    'type' => 'text/html',
                    'value' => ' '
                )
            ),
            'template_id' => $this->getContainer()->getParameter('template_new_company_follower_id'),
            'asm' => array(
                'group_id' => $this->getContainer()->getParameter('group_followers')
            )
        );
        Utility::sendgrid_mail(json_encode($params));
    }

}
