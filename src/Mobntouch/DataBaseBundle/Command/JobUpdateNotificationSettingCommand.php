<?php

namespace Mobntouch\DataBaseBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class JobUpdateNotificationSettingCommand extends ContainerAwareCommand {

    protected function configure() {
        $this->setName('Job:UpdateNotificationSetting')
                ->setDescription('Update sendgrid mail notification')
                //->addArgument('userId')
                ->addArgument('group')
                ->addArgument('email')
                ->addArgument('status');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        set_time_limit(0);
        // Set up database
        $dm = $this->getContainer()->get('doctrine_mongodb.odm.document_manager');
        //$userId = $input->getArgument('userId');
        $group = $input->getArgument('group');
        $email = $input->getArgument('email');
        $status = $input->getArgument('status');
        
        /*$user = $dm->createQueryBuilder('DataBaseBundle:User')
                ->field('id')->equals($userId)
                ->getQuery()
                ->getSingleResult();
        
        if (!$user) {
            return;
        }*/
        
        $this->updateSuppressions($group, $status, $email);

        //$dm->flush();
        //$dm->clear();
    }

    private function updateSuppressions($group, $status, $email) {
        $url = "https://api.sendgrid.com/v3/asm/groups/{$group}/suppressions/{$email}";
        if($status && strtolower($status) == 'true'){
            $url = "https://api.sendgrid.com/v3/asm/groups/{$group}/suppressions";
        }
        $curl = curl_init($url);
        // Tell PHP not to use SSLv3 (instead opting for TLS)
        //curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->getContainer()->getParameter('sendgrid_secret_key'), 'Content-Type: application/json'));
        
        if($status && strtolower($status) == 'true'){
            // Add on unsubscribe group
            curl_setopt($curl, CURLOPT_POST, true);
            // Tell curl that this is the body of the POST
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array('recipient_emails' => array($email))));
        }else{
            // Remove from unsubscribe group
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }
        // Tell curl not to return headers, but do return the response
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //Turn off SSL
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //New line
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); //New line
        // obtain response
        $response = curl_exec($curl);
        curl_close($curl);
        //echo json_encode($response);
        return json_decode($response);
    }

}
