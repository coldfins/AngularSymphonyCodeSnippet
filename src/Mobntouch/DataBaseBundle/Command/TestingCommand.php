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




class TestingCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('testing:zoho')
            ->setDescription('Testing Zoho')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $email = "josep@mediaswapp.com";
        $getURL= "https://crm.zoho.com/crm/private/json/Contacts/getSearchRecordsByPDC";
        $getQuery= "authtoken=3a4e265995c597d3910679950d884a43&scope=crmapi&selectColumns=accountid&searchColumn=email&searchValue=".$email;

        $getch = curl_init();
        /* set url to send post request */
        curl_setopt($getch, CURLOPT_URL, $getURL);
        /* allow redirects */
        curl_setopt($getch, CURLOPT_FOLLOWLOCATION, 1);
        /* return a response into a variable */
        curl_setopt($getch, CURLOPT_RETURNTRANSFER, 1);
        /* times out after 30s */
        curl_setopt($getch, CURLOPT_TIMEOUT, 30);
        /* set POST method */
        curl_setopt($getch, CURLOPT_POST, 1);
        /* add POST fields parameters */
        curl_setopt($getch, CURLOPT_POSTFIELDS, $getQuery);// Set the request as a POST FIELD for curl.

        //Execute cUrl session
        $JSONresponse = curl_exec($getch);
        try{
            $response = json_decode($JSONresponse);
            curl_close($getch);
            $id = $response->response->result->Contacts->row->FL->content;
            //print_r($id);

            $xml =
                '<?xml version="1.0" encoding="UTF-8"?>
                <Contacts>
                <row no="1">
                <FL val="Mobintouch">IN</FL>
                </row>
                </Contacts>';
            $auth="3a4e265995c597d3910679950d884a43";
            $url ="https://crm.zoho.com/crm/private/xml/Contacts/updateRecords";
            $query="authtoken=".$auth."&scope=crmapi&newFormat=1&id=$id&xmlData=".$xml;
            $ch = curl_init();
            // set url to send post request
            curl_setopt($ch, CURLOPT_URL, $url);
            // allow redirects
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            // return a response into a variable
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // times out after 30s
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            // set POST method
            curl_setopt($ch, CURLOPT_POST, 1);
            // add POST fields parameters
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query);// Set the request as a POST FIELD for curl.

            //Execute cUrl session
            $response = curl_exec($ch);
            curl_close($ch);
            //echo $response;

        } catch (\Exception $e) {
            //print_r("Exception");
        }

    }


}

